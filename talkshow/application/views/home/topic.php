<?php
if ($topic === false) {
    $topic = "没有此话题";
}
?>
<section class="header">
    <div class="logo_menu">
        <div class="logo">logo</div>
        <div class="menu">
            <ul>
                <li><a href="<?php echo base_url() ?>home/talk/java">JAVA</a></li>
                <li><a href="<?php echo base_url() ?>home/talk/php">PHP</a></li>
            </ul>
        </div>
    </div>
</section>
<section class="main_topic">
    <div class="talk_box">
        <div class="title"><?php echo $topic; ?> 聊天</div>
        <ul class="chats cool-chat">             

        </ul>
        <div class="talk"><textarea class="message_box topic_area_box" placeholder="Press CTRL +  Enter to send"></textarea></div>
    </div>
</section>

<section class="second_topic">
    <div class="talk_box">
        <div class="title"><?php echo $topic; ?> 话题聊天</div>
        <div class="subtopic_list">
            <ul>

            </ul>
        </div>
        <div class="subtopic_chat">
            <ul class="chats cool-chat notop">             

            </ul>
        </div>
        <div class="talk"><textarea class="message_box sub_topic_area_box" placeholder="Press CTRL + Enter to send"></textarea></div>
    </div>
</section>
<section class="finally_list">finally_list</section>



<script type="text/javascript">
    var topic = "<?php echo $topic; ?>";
    var subtopicing = "";
    if (topic != '没有此话题')
    {
        WSCO.init(topic);
        $(".topic_area_box").on('keydown', function (e) {
            if (e.ctrlKey && e.which == 13 || e.which == 10) {
                var subtopic = $(".topic_area_box").attr("subtopic");
                if (subtopic == null || subtopic == undefined)
                {
                    subtopic = '';
                }
                messageObj.sendTopicMessage(topic, subtopic, $(".topic_area_box").val());
                $(".topic_area_box").val("");
                $(".topic_area_box").removeAttr("subtopic");
                $(".topic_area_box").attr("palceholder", "Press CTRL + Enter to send");
            }
        });

        $(".sub_topic_area_box").on('keydown', function (e) {
            if (e.ctrlKey && e.which == 13 || e.which == 10) {

                if (subtopicing != null && subtopicing != undefined && subtopicing != "")
                {
                    messageObj.sendTopicMessage(topic, subtopicing, $(".sub_topic_area_box").val());
                    $(".sub_topic_area_box").val("");
                } else
                {
                    alert("还没有选中的子话题！");
                }
            }
        });

        $(".subtopic_list").delegate("li", "click", function () {
            var subtopicid=$(this).attr("subtopicid");
            subtopicing=subtopicid;
            $(".subtopic_chat ul").html("");
        });
        
    }








    $(function () {
        setInterval(function () {
            $.get("<?php echo base_url() ?>home/time");
        }, 60 * 5 * 1000);
    });

    function reciveMessage(message)
    {
        var templateStr = buildChatItem(message);
        $(".main_topic .chats").append(templateStr);
        console.info("topic:" + message.data.subtopic + ":::" + subtopicing);
        if (message.data.subtopic == subtopicing && subtopicing != "")
        {
            $(".subtopic_chat .chats").append(templateStr);
        }
    }

    function createSubtopic(amessageSpan)
    {
        var word = amessageSpan.innerHTML;
        var message = messageCreateTool.create("command/getsubtopicid", "{\"topic\":\"" + topic + "\",\"word\":\"" + word + "\"}", "serverBackSubtopicId", null);
        WSCO.sendMessage(message, null);
    }
    function serverBackSubtopicId(message)
    {

        $(".topic_area_box").attr("placeholder", "new subtopic #" + message.data.subtopicid);
        $(".topic_area_box").attr("subtopic", message.data.subtopicid);
        var subtopicbox = $(".subtopic_list ul");
        var subtopiclist = $(".subtopic_list li");

        if (subtopiclist.length >= 5)
        {
            $(".subtopic_list li:last").replaceWith("<li subtopicid=\""+message.data.subtopicid+"\">" + message.data.word + "</li>");
        } else
        {
            subtopicbox.append("<li subtopicid=\""+message.data.subtopicid+"\">" + message.data.word + "</li>");
        }
        

        subtopicing = message.data.subtopicid;
        
        //干掉子话题聊天记录
        $(".subtopic_chat ul").html("");
    }

    function subTopicListbindClick()
    {

    }


    function buildChatItem(chatItem)
    {
        var subtopic = "";
        if (chatItem.data.subtopic)
        {
            subtopic = " #" + chatItem.data.subtopic;
        }
        var tempStr = '<li class="in"><img src="/images/user1.png" alt="" class="avatar"><div class="message">';
        tempStr += '<span class="arrow"></span><a class="name" href="#">' + chatItem.data.from + '</a><span class="datetime">' + getLocalTime(chatItem.data.ctime) + subtopic + '</span>';
        tempStr += '<span onclick="createSubtopic(this)" class="body">' + chatItem.data.message + '</span></div></li>';

        return tempStr;
    }


    function getLocalTime(nS) {
        return new Date(parseInt(nS) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
    }


</script>

