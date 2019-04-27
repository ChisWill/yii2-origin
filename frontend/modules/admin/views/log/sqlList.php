<style type="text/css">
    .sql-list {
        background: #fff;
    }
    [data-sortcol] {
        cursor: pointer;
    }
</style>

<?= $html ?>

<script>
$(function () {
    $(".list-container").on('click', '.task-row', function () {
        var key = $(this).data('key');
        if ($(".tr-task-" + key).length === 0) {
            var category,
                $this = $(this),
                data = $this.data('extra'),
                $nextTr = $("<tr>");
            $this.after($nextTr);
            $nextTr.before('<tr class="sql-list tr-task-' + key + '"><th>序号</th><th>语句类别</th><th colspan="2">SQL</th><th data-sortcol="3" class="sort-th' + key + '">执行时间（ms）</th><th>时间差（ms）</th><th>总时长（ms）</th></tr>');
            for (var k in data) {
                if (data[k].category.indexOf('query') !== -1) {
                    category = 'query';
                } else {
                    category = 'execute';
                }
                $nextTr.before('<tr class="sql-list tr-task-' + key + '" title="' + data[k].trace + '"><td>' + (Number(k) + 1) + '</td><td>' + category + '</td><td colspan="2">' + data[k].sql + '</td><td>'+ data[k].duration + '</td><td>' + data[k].diff + '</td><td>' + data[k].time + '</td></tr>');
            }
            $nextTr.remove();
            $(this).addClass('active');

            // 根据执行时间排序事件
            $('.sort-th' + key).click(function () {
                var $list = $('.tr-task-' + key + ':gt(0)'),
                    $header = $('.tr-task-' + key + ':eq(0)'),
                    sortcol = $(this).data('sortcol'),
                    factor = sortcol - 1,
                    $x, $y;

                $list.sort(function (x, y) {
                    $x = Number($(x).find('td:eq(' + sortcol + ')').html());
                    $y = Number($(y).find('td:eq(' + sortcol + ')').html());
                    if ($x > $y) {
                        return -1 * factor;
                    } else if ($x < $y) {
                        return 1 * factor;
                    } else {
                        return 0;
                    }
                });

                if (sortcol == $(this).index()) {
                    $(this).data('sortcol', 0);
                } else {
                    $(this).data('sortcol', $(this).index())
                }

                $header.after($list.detach());
            });
        } else {
            if ($(this).hasClass('active')) {
                $(".tr-task-" + key).hide();
                $(this).removeClass('active');
            } else {
                $(".tr-task-" + key).show();
                $(this).addClass('active');
            }
        }
    });
});
</script>