const SimpleShopAlert = {
    show: function(config) {
        Swal.fire({
            ...config,
            backdrop: `
                rgba(0,0,123,0.4)
                url("${simpleShopConfig.pluginUrl}/assets/images/pattern.svg")
            `,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    },

    success: function(message, title = null) {
        this.show({
            icon: 'success',
            title: title || simpleShopAlerts.defaultTitle,
            text: message
        });
    },

    error: function(message, title = null) {
        this.show({
            icon: 'error',
            title: title || __('Error', 'simple-shop'),
            text: message
        });
    },

    confirm: function(message, callback, config = {}) {
        this.show({
            title: config.title || __('Confirm', 'simple-shop'),
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: config.confirmText || __('Yes', 'simple-shop'),
            cancelButtonText: config.cancelText || __('No', 'simple-shop'),
            ...config
        }).then((result) => {
            if (result.isConfirmed && typeof callback === 'function') {
                callback();
            }
        });
    }
};