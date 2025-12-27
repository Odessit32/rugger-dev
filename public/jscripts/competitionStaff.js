/**
 * Competition Staff Table - jQuery version
 * Replaces AngularJS competitionStaffListController
 */
(function($) {
    'use strict';

    window.CompetitionStaff = {
        data: {},
        loading: false,
        container: null,
        apiUrl: 'ajax.php',

        init: function(containerId, initialData) {
            this.container = $('#' + containerId);
            if (!this.container.length) return;

            this.data = {
                competition_id: initialData.competition_id || 0,
                championship_id: initialData.championship_id || 0,
                points: initialData.points || []
            };

            // Cache initial data
            this.data.pointsCache = this.data.points;

            // Render initial data
            this.render(this.data.points);

            // Bind click events
            this.bindEvents();
        },

        bindEvents: function() {
            var self = this;
            this.container.find('.sorting-title').on('click', function() {
                var type = $(this).data('type');
                if (type) {
                    self.getStaff(type);
                }
            });
        },

        getStaff: function(type) {
            var self = this;
            var typeKey = this.getTypeKey(type);

            // Check cache first
            if (this.data[typeKey] && this.data[typeKey].length > 0) {
                this.render(this.data[typeKey]);
                return;
            }

            // Load from server
            if (this.loading) return;
            this.loading = true;

            $.ajax({
                method: 'POST',
                url: this.apiUrl,
                data: {
                    action: 'get_competition_staff',
                    championship_id: this.data.championship_id,
                    type: typeKey
                },
                dataType: 'json',
                success: function(response) {
                    if (response && response.staff && response.staff.length > 0) {
                        self.data[typeKey] = response.staff;
                        self.render(response.staff);
                    }
                    self.loading = false;
                },
                error: function() {
                    self.loading = false;
                }
            });
        },

        getTypeKey: function(type) {
            var map = {
                'p': 'points',
                't': 'pop',
                'sh': 'sht',
                'r': 'pez',
                'dg': 'd_g',
                'zhk': 'y_c',
                'kk': 'r_c'
            };
            return map[type] || 'points';
        },

        render: function(staffList) {
            var tbody = this.container.find('tbody');
            tbody.empty();

            if (!staffList || staffList.length === 0) {
                tbody.append('<tr><td colspan="9">Нет данных</td></tr>');
                return;
            }

            for (var i = 0; i < staffList.length; i++) {
                var staff = staffList[i];
                var row = '<tr>' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td class="title">' + (staff.title || '') + '</td>' +
                    '<td>' + (staff.points || 0) + '</td>' +
                    '<td>' + (staff.pop || 0) + '</td>' +
                    '<td>' + (staff.sht || 0) + '</td>' +
                    '<td>' + (staff.pez || 0) + '</td>' +
                    '<td>' + (staff.d_g || 0) + '</td>' +
                    '<td>' + (staff.y_c || 0) + '</td>' +
                    '<td>' + (staff.r_c || 0) + '</td>' +
                    '</tr>';
                tbody.append(row);
            }
        }
    };

})(jQuery);
