<script src="js/jstree.min.js"></script>

<div class="off-canvas-wrap">
    <div class="inner-wrap">
        <!-- Off Canvas Menu -->
    <aside class="left-off-canvas-menu">
        <!-- whatever you want goes here -->
        <div id="tree_column_small">
            <ul class="button-group" id="tree_control_small">
               <li class="tree_button"><a href="#" class="small button secondary">[STRING_CONTENT_ADDGROUP]</a></li>
               <li class="tree_button"><a href="#" class="small button secondary">[STRING_CONTENT_ADDENTRY]</a></li>
            </ul>
            <div id="tree_small">
            </div>
        </div>
    </aside>
        <a class="left-off-canvas-toggle hide-for-medium-up treemenu">[STRING_CONTENT_OPENMENU]</a> 
            <div class="row">
                <div class="hide-for-small medium-1 columns">&nbsp;</div>
                <div id="content" class="small-12 medium-10 columns panel" style="margin-bottom:0px;">
                    <div class="row" style="height:100%;">
                        <div id="tree_column_large" class="hide-for-small medium-5 columns">
                            <ul class="button-group" id="tree_control_large">
                                <li class="tree_button"><a href="#" class="small button secondary">[STRING_CONTENT_ADDGROUP]</a></li>
                                <li class="tree_button"><a href="#" class="small button secondary">[STRING_CONTENT_ADDENTRY]</a></li>
                            </ul>
                          <div class="treeback">
                            <div id="tree_large">
                            </div>
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
$('#tree_large').jstree({'core' : {
        "check_callback":true,
    'data' : [
       { "id" : "ajson1", "parent" : "#", "text" : "Simple root node" },
       { "id" : "ajson2", "parent" : "#", "text" : "Root node 2" },
       { "id" : "ajson3", "parent" : "ajson2", "text" : "Child 1" },
       { "id" : "ajson4", "parent" : "ajson2", "text" : "Child 2" },
    ]
},
 'plugins': ["dnd","wholerow"]
});
$('#tree_small').jstree({'core' : {
    "check_callback":true,
    'data' : [
       { "id" : "ajson11", "parent" : "#", "text" : "Simple root node" },
       { "id" : "ajson21", "parent" : "#", "text" : "Root node 2" },
       { "id" : "ajson31", "parent" : "ajson21", "text" : "Child 1" },
       { "id" : "ajson41", "parent" : "ajson21", "text" : "Child 2" },
    ]
},
 'plugins': ["dnd","wholerow"]
});

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