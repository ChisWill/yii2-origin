<style>
    .linkage-container {
        float: left;
        min-width: 180px;
        width: 180px;
        margin-right: 10px;
        border: 1px solid #eee;
        height: 100%;
        overflow-y: scroll;
    }
    body {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0px;
        left: 0px;
    }
    .list-container {
        float: left;
        width: calc(100% - 200px);
        height: 100%;
        overflow-y: auto;
    }
    .list-container::-webkit-scrollbar, .linkage-container::-webkit-scrollbar {
        width: 8px;
        background-color: #fff;
    }
    .list-container::-webkit-scrollbar-thumb, .linkage-container::-webkit-scrollbar-thumb {
        border-radius: 8px;
        background: #eee;
    }
    .linkage-ul > li > div p {
        border-bottom: hidden;
        padding-left: 5px;
        cursor: pointer;
    }
    .linkage-header {
        border: none;
        border-bottom: 1px solid #eee;
    }
    .linkage-header p {
        width: 100% !important;
        text-align: left;
        height: 30px;
        line-height: 30px;
        padding-left: 0px;
        border: none;
    }
    .linkage-row p {
        border-right: hidden;
    }
    .linkage-row-selected {
        background: #93dfdb;
        font-weight: bold;
    }
    .linkage-row-selected a {
        display: inline-block;
        color: #fff;
    }
    .page-container:after {
        content: "";
        display: block;
        clear: both;
    }
    .page-container {
        height: calc(100% - 100px);
    }
    .list-box {
        height: 100%;
    }
    .linkage-row p:hover a {
        color: #1dbeba !important;
    }
    .linkage-container label input {
        z-index: 50;
    }
    .linkage-container label .categoryItem {
        display: inline-block;
        margin-left: -15px;
        text-indent: 15px;
        z-index: 100;
    }
    @media (max-width: 767px) {
        .page-container {
            overflow-x: auto;
        }
        .list-box {
            min-width: 800px;
            overflow-x: auto;
        }
        .table-container table {
            min-width: auto;
        }
    }

</style>
<div class="list-box">
    <?= $menuHtml ?>
    <?= $articleHtml ?>
</div>

<script>
$(function () {
    $(".page-container .search [name='search[menu.id]']").children().each(function () {
        if ($(this).html().indexOf('★') === 0) {
            $(this).attr('disabled', 'disabled');
        }
    });

    // 请求 
    var getArticles = function (categories) {
        var index = $.loading();
        $.get('', {
            'search[categories]': categories
        }, function (msg) {
            layer.close(index);
            $(".list-container").find('.list-view').html(msg.info);
        });
    };

    // 存放选中的id
    $(".linkage-container").on('click', '.linkage-row input[type="checkbox"]', function () {
        var categories = [];
        var $this = $(this);
        $(".linkage-row-selected").removeClass('linkage-row-selected');
        $this.addClass('linkage-row-selected');

        if ($this.is(':checked')) {
            $this.parents('.linkage-row').siblings('ul').find('li').each(function () {
                $(this).find('.linkage-row input').prop("checked", 'checked');
            });
        } else {
            $this.parents('.linkage-row').siblings('ul').find('li').each(function () {
                $this.parents('.linkage-row').siblings('ul').find('li input').removeAttr('checked');
            });
        }

        $(".linkage-ul input[type='checkbox']:checked").each(function () {
            categories.push($(this).parents(".linkage-row").data('key'));
        });

        getArticles(categories);
        var href = $(".list-container").find('#addBtn').attr('href');
        $(".list-container").find('#addBtn').attr('href', href + '?pid=' + $this.parents('.linkage-row').find(".categoryItem").data('id'));
    });
    // 全部分类
    $(".linkage-container").on('click', '.linkage-header input[type="checkbox"]', function () {
        getArticles();
    });
});
</script>