/**
 * Created by wuzhc on 2016/11/25.
 */

(function ($) {
    $.extend({
        showBox: function (options) {

            var config = $.extend({
                selector: "showMsgModal",
                seconds: 1500,
                isClose: true,
                title: "提示信息",
                msg: "this is a message",
                callback: null,
                width: 400
            }, options);

            var $self = $("#" + config.selector);

            // 初始化配置
            $self.find(".modal-title").text(config.title);
            $self.find(".modal-body>p").text(config.msg);
            $self.find(".modal-dialog").css({"width": config.width + "px"});

            //当设置手动关闭时候，显示关闭按钮
            if (!config.isClose) {
                $self.find(".modal-header").removeClass("none");
                $self.find(".modal-footer").removeClass("none");
                $self.find(".close").removeClass("none");
            }

            // 显示弹窗
            $self.modal('show');

            if (config.isClose) {
                setTimeout(function () {
                    $self.modal("hide");
                }, config.seconds)
            }

            // 关闭窗口后函数回调
            if (config.callback) {
                var callback = config.callback;
                $self.on('hidden.bs.modal', function (e) {
                    callback();
                })
            }
        }
    });
})(jQuery);