(function () {
    "use strict";

    var migrateAccountButton, migrateJobsButton, migrationStatusItem;

    jQuery(document).ready(function () {
        "use strict";

        migrationStatusItem = jQuery('#icl-jobs-migration-status');
        migrateAccountButton = jQuery('#p_icl_account_exists').find('button');
        migrateJobsButton = jQuery('#wpml_icl_migrate_jobs');
        migrateAccountButton.click(runICLTPAccountMigration);
        migrateJobsButton.click(runICLTPJobsMigration);
    });

    var spinnerHTML = '<span class="spinner" style="float: left;"></span>';
    var nonce = jQuery('[name="upgrade_icl_account_nonce"]').val();

    function runICLTPAccountMigration() {
        var self = jQuery(this);
        self.attr('disabled', 'disabled');
        var spinner = jQuery(spinnerHTML);
        self.parent().append(spinner);
        spinner.css('visibility', 'visible');

        jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'upgrade_icl_account',
                _icl_nonce: nonce
            },
            success: function (response) {
                if (response.success) {
                    migrateJobsButton.attr('disabled', false);
                    spinner.hide();
                    self.hide();
                    maybeShowJobCountRow();
                } else {
                    self.parent().append(jQuery('<span class="error">' + response.data + '</span>'));
                    spinner.remove();
                }
            }
        });
    }

    function runICLTPJobsMigration() {
        var self = jQuery(this);
        self.attr('disabled', 'disabled');
        var spinner = jQuery(spinnerHTML);
        self.parent().append(spinner);
        spinner.css('visibility', 'visible');
        migrationStatusItem.show();
        jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'upgrade_icl_jobs',
                _icl_nonce: nonce
            },
            success: function (response) {
                if (!!response.success) {
                    migrateOneJob();
                    self.hide();
                } else {
                    self.parent().append(jQuery('<span class="error">' + response.data + '</span>'));
                    spinner.remove();
                }
            }
        });
    }

    function maybeShowJobCountRow() {
        var statusSpan = jQuery('#p_icl_jobs_exist_count').find('#icl-jobs-left-count');
        jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'icl_job_count',
                _icl_nonce: nonce
            },
            success: function (response) {
                var count = response.data;
                statusSpan.html(count);
                if (count) {
                    statusSpan.parent().show()
                }
            }
        });
    }

    function migrateOneJob() {
        var statusSpan = jQuery('#p_icl_jobs_exist_count').find('#icl-jobs-left-count');
        jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'migrate_one_job',
                _icl_nonce: nonce
            },
            success: function (response) {
                if (!!response.success) {
                    statusSpan.text(response.data);
                    if (response.data) {
                        migrateOneJob();
                    } else {
                        migrationStatusItem.hide();
                        jQuery('.spinner').hide();
                    }
                }
            }
        });
    }
})();