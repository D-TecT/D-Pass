<script src="js/jstree.min.js"></script>

<div class="off-canvas-wrap">
    <div class="inner-wrap">
        <!-- Off Canvas Menu -->
    <aside class="left-off-canvas-menu">
        <!-- whatever you want goes here -->
        <div id="jstree_demo_div2" style="background-color:lightgrey; min-height:100%;">
                                  </div>
    </aside>
            <div class="row">
                <div class="hide-for-small medium-1 columns">&nbsp;</div>
                <div id="content" class="small-12 medium-10 columns panel" style="margin-bottom:0px;">
        <a class="left-off-canvas-toggle hide-for-medium-up" >Menu</a> 
                    <div class="row" style="height:100%;">
                        <div class="hide-for-small medium-5 columns treeback">
                                <div id="jstree_demo_div1">
                                  </div>
                        </div>
                        <div class="small-12 medium-7 columns">
                        </div>
                    </div>
                </div>
                <div class="hide-for-small medium-1 columns">&nbsp;</div>
            </div>
        
        <a class="exit-off-canvas"></a>
    </div>
</div>

<script>
$('#jstree_demo_div2').jstree({'core' : {
    'data' : [
       { "id" : "ajson1", "parent" : "#", "text" : "Simple root node" },
       { "id" : "ajson2", "parent" : "#", "text" : "Root node 2" },
       { "id" : "ajson3", "parent" : "ajson2", "text" : "Child 1" },
       { "id" : "ajson4", "parent" : "ajson2", "text" : "Child 2" },
    ]
} });
$('#jstree_demo_div1').jstree({'core' : {
    'data' : [
       { "id" : "ajson11", "parent" : "#", "text" : "Simple root node" },
       { "id" : "ajson21", "parent" : "#", "text" : "Root node 2" },
       { "id" : "ajson31", "parent" : "ajson21", "text" : "Child 1" },
       { "id" : "ajson41", "parent" : "ajson21", "text" : "Child 2" },
    ]
} });

$(document).ready(sizeContent);
$(window).resize(sizeContent);
function sizeContent() {
    var emSize=parseFloat($("body").css("font-size"));
    var newHeight=$("html").height()-$("#content").offset().top;
    if (document.documentElement.clientWidth >= emSize*40.063) {
      newHeight=newHeight-emSize;    
    }
    //console.log(newHeight);
    $("#content").css("height", newHeight);
}

</script>