<?php $this->regCss(['common', 'user']) ?>
<?php $this->title = t('Account') ?>

<div class="content">
    <span class="title">用户基本信息设置</span>
    <div class="content-wrap clear-fix">
        <div class="wrap-left fl">
            <div class="user-active" data-tab="tab-userinfo">基本信息</div>
            <div data-tab="tab-password">修改密码</div>
            <div data-tab="tab-consume">消费记录</div>
        </div>
        <div class="wrap-right fr">
            <!-- 基本信息 -->
            <div class="baseinfo" id="tab-userinfo">
                <div>
                    <span>邮箱</span>
                    <input class="email-text" type="text" value="1205763462@qq.com" />
                </div>
                <div class="sex">
                    <span>性别</span>
                    <span class="male gender">
                         <label for="sex">男</label>
                        <span class="radio">
                           <span class="active"></span>
                        </span>
                    </span>
                    <span class="female gender">
                        <label for="sex">女</label>
                        <span class="radio">
                            <span></span>
                        </span>
                    </span>  
                </div>
                <div>
                    <span>地区</span>
                    <input class="address-text" type="text" value="陕西省西安市"  />
                </div>
                <div>
                    <span>登录次数</span>
                    <span>12</span>
                </div>
                <div>
                    <span>会员状态</span>
                    <span>非会员</span>
                </div>
                <div>
                    <span>注册时间</span>
                    <span>2017-05-04 12:23:34</span>
                </div>
                <span>
                    <input id="modifyBtn" type="button" value="保存" />
                </span>
            </div>
            <!-- 修改密码 -->
            <div class="modify hidden" id="tab-password">
                <div>
                    <span>账户</span>
                    <span class="username">123444444</span>
                </div>
                <div>
                    <span>旧密码</span>
                    <input type="password" />
                </div>
                <div>
                    <span>新密码</span>
                    <input type="password" />
                </div>
                <div>
                    <span>确认密码</span>
                    <input type="password" />
                </div>
                <div>
                    <input id="confirmBtn" type="button" value="确认" />
                </div>
            </div>
            <!-- 消费记录 -->
            <div class="record hidden" id="tab-consume">
                <div class="clear-fix">
                    <span class="fl">2017-05-06</span>
                    <span class="fr">￥100.00</span>
                </div>
                <div class="clear-fix">
                    <span class="fl">2017-05-06</span>
                    <span class="fr">￥100.00</span>
                </div>
                <div class="clear-fix">
                    <span class="fl">2017-05-06</span>
                    <span class="fr">￥100.00</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<script>
$(function () {
    $(".gender").click(function () {
        $(this).children().eq(1).children().addClass("active");
        $(this).siblings(".gender").children().eq(1).children().removeClass("active");
    });

    $(".wrap-left>div").click(function () {
        $(this).addClass('user-active').siblings().removeClass('user-active');
        $("#" + $(this).data('tab')).show().siblings().hide();
    });
});
</script>