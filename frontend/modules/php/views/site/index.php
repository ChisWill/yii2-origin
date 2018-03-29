<?php $this->regCss(['common', 'index']) ?>
<?php $this->title = t('PHP Online Encrypt|PHP Online Obfuscate|PHP Encrypt|PHP Obfuscate') ?>

<div class="content text-center">
    <!-- 简介 -->
    <div class="aboutme">
        <div class="aboutme-title">
            <p class="font-30">PHP在线加密平台简介</p>
            <p class="color-gray">Brief Introduction of PHP Online Encryption Platform</p>
            <div class="border-img"></div>
        </div>
        <div class="aboutme-content">
            <p>PHP在线加密平台是一个优秀的免费的PHP源码加密保护平台，PHP代码加密后无需依靠附加扩展来解析，服务器端无需安装任何第三方组件，可运行于任何普通PHP环境下。虽然加密的强度较高，但会在运行时会占用一定的内存资源，我们只推荐加密ciass或function主要核心引用文件（不准推荐所有文件都加密）。</p>
        </div>
    </div>
    <!-- 技术特色 -->
    <div class="tech">
        <div class="tech-title">
            <p class="font-30">技术特色</p>
            <p class="color-gray">Technical Characteristics</p>
            <div class="border-img"></div>
        </div>
        <div class="tech-content">
            <p>PHP加密采用了强大的安全技术手段（外壳），对PHP源码进行混淆变异（源码），系统安全防修改、防劫持、防破解（扩展），三重保护， 更加安全！</p>
        </div>
        <div class="tech-icon">
            <!-- <img src="/images/icon.png"> -->
        </div>
    </div>
    <!-- 效果对比图 -->
    <div class="platform">
        <div class="platform-title">
            <p class="font-30">加密平台效果对比图</p>
            <p class="color-gray">Comparison of Encryption Platform Effect</p>
            <div class="border-img"></div>
        </div>
        <div class="encrypticon">

        </div>
        <!-- <img class="encrypticon" src="/images/encryptimg.png"> -->
    </div>
    <div class="question">
        <div class="question-title">
            <p class="font-30">PHP加密常见问题</p>
            <p class="color-gray">PHP Encryption Frequently Asked Questions</p>
            <div class="border-img"></div>
        </div>
        <div class="question-list">
            <ul>
                <li><span class="color-red">Q：</span>加密后的PHP文件还能编辑吗？</li>
                <li><span class="color-yellow">A：</span>不可以，并禁止二次加密，因为这样也是修改了PHP文件加密编码。</li>
                <li><span class="color-red">Q：</span>我该如何上传加密后的PHP文件？</li>
                <li><span class="color-yellow">A：</span>
                    <p>使用ftp对PHP加密文件传输时，请使用二进制模式传输，当在本地测试正常而传到服务器后不正常时，请检查一下本地和服务器上的文件大小是否相同（以字节为精确度），不相同时可尝试换一个FTP客户端软件。（目前已发现FlashFXP工具对一些Linux服务器上传会有问题，建议使用FileZilla）</p>
                </li>
                <li><span class="color-red">Q：</span>能加密html文件吗？</li>
                <li><span class="color-yellow">A：</span>
                    <p>不可以。可以先把html代码转换到php文件中输出，但在浏览器中访问后查看源代码还是未加密的html代码。</p>
                </li>
            </ul>
        </div>
    </div>
</div>