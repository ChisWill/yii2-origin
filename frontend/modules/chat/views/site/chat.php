<div id="chatBox" class="chat-box hide">
    <input type="hidden" class="serverTime" value="<?= time() ?>">
    <input type="hidden" class="localTime" value="">
    <div class="box-title moveBox">
        &#8203;
    </div>
    <div class="chat-content">
        <!-- 左侧列表 -->
        <ul class="chat-list"></ul>
        <!-- 右侧聊天框 -->
        <div class="chat-container chatContainer">
            <div class="chat-about">
                <div class="chat-title">
                    <div class="chat-other chatTargetArea">
                        <input type="hidden" class="targetUserId">
                        <img class="chat-headicon" src="">
                        <span class="chat-username userName"></span>
                    </div>
                </div>
                <div class="chat-main">
                    <ul>
                    </ul>
                    <div class="upload-progress">
                        <div class="upload-bar" style="width: 0%"></div>
                    </div>
                </div>
                <div class="chat-footer">
                    <div class="chat-tool">
                        <span class="chat-icon tool-image" title="上传图片">
                            
                            <form id="picture" enctype="multipart/form-data">
                                <input type="file" name="Upload[image]" id="inputImage" accept="images/*" capture="camera">
                            </form>
                        </span>
                        <span class="chat-icon tool-image" title="发送文件">
                            
                            <form id="file" enctype="multipart/form-data">
                                <input type="file" id="inputFile" name="Upload[file]" />
                            </form>
                        </span>
                    </div>
                    <div class="chat-textarea" id="chatTextArea" contenteditable="true" style="-webkit-user-select:auto;"></div>
                    <div class="chat-bottom">
                        <div class="chat-send">
                            <span class="send-close close-chat closeChat">关闭</span>
                            <span class="send-btn sendBtn">发送</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span class="setwin">
        <a class="close-chat closeChat" href="javascript:;"></a>
    </span>
</div>