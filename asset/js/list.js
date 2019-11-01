angular.module('reserve')
    .config(['$routeProvider', 'piProvider', 'config',
        function ($routeProvider, piProvider, config) {

            //Get template url
            function tpl(name) {
                return config.assetRoot + name + '.html'
            }

            function resolve(action) {
                return {
                    data: ['$q', '$route', '$rootScope', '$location', 'server',
                        function ($q, $route, $rootScope, $location, server) {
                            var deferred = $q.defer()
                            var params = $route.current.params
                            $('.ajax-spinner').show()

                            $rootScope.alert = 2
                            server.get(action, params).success(function (data) {
                                data.filter = params
                                deferred.resolve(data)
                                $rootScope.alert = ''
                            })
                            return deferred.promise
                        }
                    ]
                }
            }

            $routeProvider.when('/search', {
                templateUrl: tpl(config.template),
                controller: 'ListCtrl',
                resolve: resolve('search')
            }).otherwise({
                redirectTo: '/search'
            })

            piProvider.setHashPrefix()
            piProvider.addTranslations(config.t)
            piProvider.addAjaxInterceptors()
        }
    ])
    .service('server', ['$http', '$cacheFactory', 'config',
        function ($http, $cacheFactory, config) {

            var urlRoot = config.urlRoot

            this.get = function (action, params) {
                return $http.get(urlRoot + action, {
                    params: params
                })
            }

            this.filterEmpty = function (obj) {
                var search = {}
                for (var i in obj) {
                    if (obj[i]) {
                        search[i] = obj[i]
                    }
                }
                return search
            }
        }
    ])
    .controller('ListCtrl', ['$scope', '$route', '$location', 'data', 'config', 'server',
        function ($scope, $route, $location, data, config, server) {

            angular.extend($scope, data)

            // Set for reload
            $scope.reloadRoute = function () {
                $location.search('autoReload', 'true')
                $route.reload()
            }

            $scope.$watch('paginator.page', function (newValue, oldValue) {
                if (newValue === oldValue) return
                $location.search('page', newValue)
            })

            $scope.filterAction = function () {
                $location.search(server.filterEmpty($scope.filter))
                $location.search('page', null)
            }

            $('.ajax-spinner').hide()

            // Set export url
            if (config.urlExport) {
                var url = window.location.href
                url = url.substring(url.indexOf('#') + 1)
                url = url.replace('!/search', '')
                exportUrl = config.urlExport + url
                $('.panel-export').attr('href', exportUrl)
            } else {
                $('.panel-export-box').addClass('hidden')
            }

            // Search by time
            $(function () {
                var d = new Date();
                var year = d.getFullYear();
                var month = d.getMonth();
                var day = d.getDate();

                var dateFormat = 'yy-mm-dd',
                    from = $('#dateFrom')
                        .datepicker({
                            dateFormat: 'yy-mm-dd',
                            defaultDate: '-2w',
                            changeMonth: true,
                            numberOfMonths: 2,
                            maxDate: new Date(year + 1, month, day),
                            minDate: new Date(year - 1, month, day)
                        })
                        .on('change', function () {
                            to.datepicker('option', 'minDate', getDate(this))
                        }),
                    to = $('#dateTo').datepicker({
                        dateFormat: 'yy-mm-dd',
                        defaultDate: '+2w',
                        changeMonth: true,
                        numberOfMonths: 2,
                        maxDate: new Date(year + 1, month, day),
                        minDate: new Date(year - 1, month, day)
                    })
                        .on('change', function () {
                            from.datepicker('option', 'maxDate', getDate(this))
                        })

                function getDate(element) {
                    var date
                    try {
                        date = $.datepicker.parseDate(dateFormat, element.value)
                    } catch (error) {
                        date = null
                    }

                    return date
                }
            })

            // list manage
            $(function () {
                var page = {
                    el: $('#item-list'),
                    modal: $('<div class="modal fade">').appendTo(document.body),
                    $: function (selector) {
                        return this.el.find(selector)
                    },
                    init: function () {
                        _.bindAll(this)
                        this.$('.schedule-edit').click(this.reserveAction)
                        this.$('.schedule-confirm').click(this.confirmAction)
                        this.$('.schedule-reject').click(this.rejectAction)
                    },
                    editAction: function (e) {
                        var p = $(e.target).parents('tr'),
                            self = this
                        $.get(p.attr('data-edit')).done(function (res) {
                            self.modal.html(res).modal('show')
                            formModule.success = function (res) {
                                self.modal.html(res).modal('hide')
                                systemMessage.succ(res.message)
                                $route.reload()
                            }
                            formModule.fail = function () {
                                self.modal.modal('hide')
                                systemMessage.fail(config.t.RESERVE_ERROR)
                            }
                        })
                    },
                    confirmAction: function (e) {
                        var p = $(e.target).parents('tr'),
                            self = this
                        systemMessage.wait(config.t.CONFIRM_AT_PROCESS)
                        let requestId = p.attr('data-id')
                        if (confirm(config.t.ARE_YOU_SURE_CONFIRM)) {
                            $.getJSON(p.attr('data-confirm')).done(function (result) {
                                if (result.status == 1) {
                                    $('#' + requestId).slideUp(700, function () {
                                        self.remove()
                                    })
                                    systemMessage.succ(result.message)
                                    //$location.search('autoReload', 'true')
                                    $route.reload()
                                } else {
                                    systemMessage.fail(result.message)
                                }
                            })
                        }

                    },
                    rejectAction: function (e) {
                        var p = $(e.target).parents('tr'),
                            self = this
                        systemMessage.wait(config.t.REJECT_AT_PROCESS)
                        let requestId = p.attr('data-id')
                        if (confirm(config.t.ARE_YOU_SURE_REJECT)) {
                            $.getJSON(p.attr('data-reject')).done(function (result) {
                                if (result.status == 1) {
                                    $('#' + requestId).slideUp(700, function () {
                                        self.remove()
                                    })
                                    $location.search('autoReload', 'true')
                                    $route.reload()
                                    systemMessage.succ(result.message)
                                } else {
                                    systemMessage.fail(result.message)
                                }
                            })
                        }
                    },
                }
                page.init()
            })
        }
    ])