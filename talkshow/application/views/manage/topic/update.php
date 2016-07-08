<h1 class="page-header">更新话题</h1>
<div class="table-responsive">
    <?php if($update->num_rows()>0){
        $update=$update->result()[0];
        ?>
    <form class="form-inline" method="post">
        <div class="form-group">
            <label for="topicname">新话题名称</label>
            <input type="hidden" name="id" value="<?php echo $update->_id.''; ?>">
            <input type="text" name="topic" class="form-control" id="topicname" value="<?php echo $update->topic.''; ?>">
        </div>
        <button type="submit" class="btn btn-default">保存</button>
    </form>
    <?php
    }
    else
    {
        echo "没找到这个话题。";
    }
    ?>
</div>



<script type="text/javascript">
    $(function () {

    });
</script>