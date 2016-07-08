<h1 class="page-header">话题列表</h1>
<div class="row">
    <div class="col-md-12">
        <a href="<?php echo base_url() ?>manage/topic/update" class="btn btn-default">新建话题</a>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>话题</th>
                <th>创建时间</th>
                <th>是否开放</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $topics = $topics->result();
            foreach ($topics as $topic) {
                ?>
                <tr>
                    <td><?php echo $topic->_id . ''; ?></td>
                    <td><?php echo $topic->topic . ''; ?></td>
                    <td><?php echo date("Y-m-d H:i:s", $topic->ctime) . '';   ?></td>
                    <td><?php echo $topic->opened . '';   ?></td>
                    <td><a href="<?php echo base_url();?>manage/topic/update/<?php echo $topic->_id . ''; ?>" class="btn btn-link">编辑</a><a onclick="return confirm('确定删除？')" href="<?php echo base_url();?>manage/topic/delthis/<?php echo $topic->_id . ''; ?>" class="btn btn-link">删除</a></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>



<script type="text/javascript">
    $(function () {
        
    });
</script>