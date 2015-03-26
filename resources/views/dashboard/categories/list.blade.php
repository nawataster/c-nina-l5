<?php
/**
 * creates recursive categories list
 * @param array $cat - category data
 * @param integer $parentId - HTML id for parent element.
 * @param integer $level- level of HTML element
 * @return string - HTML content
 */
function createCatListItem( $cat, $parentId, $level=-1 ){
	$level++;

	$del_btn	=
'<div class="btn-group btn-group-xs list-left-btn-group" role="group">'.
	'<button id="del_btn-'.$cat['id'].'" type="button" class="" title="'.trans('prompts.delete').'"></button>'.
'</div>'.
'';

	$html	=
	'<div class="panel" style="padding-left:'.(5*$level).'px;">';

	if( count($cat['children']) > 0 ){
		$this_parent_id	= 'acc-'.$cat['id'];
		$maw_id	= 'maw-'.$cat['id'];

		$html	.=
		'<div>'.
			'<div class="btn-group btn-group-xs" role="group">'.
				'<button type="button" class="btn glyphicon glyphicon-play tree-expand-btn" data-toggle="collapse" data-parent="#'.$parentId.'" data-target="#'.$maw_id.'"></button>'.
			'</div>'.
			'<span id="cat-'.$cat['id'].'" class="cat-name" role="button">'.$cat['name'].'</span>'.$del_btn.

		'</div>'.

		'<div id="'.$maw_id.'" class="panel-collapse collapse">'.
			'<div class="panel-group" id="'.$this_parent_id.'">';

		foreach($cat['children'] as $child )
			$html	.= createCatListItem( $child, $this_parent_id, $level );

		$html	.=
			'</div>'.
		'</div>'.
		'';
	}else
		$html	.=
		'<span id="cat-'.$cat['id'].'" class="cat-name cat-name-single" role="button">'.$cat['name'].'</span>'.$del_btn.
	'';

	$html	.=
	'</div>';

	return $html;
}
$main_id	= 'acc-main';
?>
@extends('layouts.left')

@section('title')
{{ @trans('prompts.categories') }}
@stop

@section('buttons')
	@include('dashboard/blocks/buttons')
@stop

@section('sidebar')
	<div class="panel-group" id="{{ $main_id }}">
	@foreach( $tree as $cat )
		{!! createCatListItem( $cat, $main_id ) !!}
	@endforeach
	</div>
@stop

@section('content')
<div id="edit-form"></div>


<div id="is-del-cat-dialog" class="" title="Basic dialog">
  Are you sure about this?
</div>

@stop

@section('js_extra')
<script type="text/javascript">

$(document).ready(function(){
	$('.cat-name').on("click", function(e) {
		var cat	= $(this).attr('id').split("-");

		$.ajax({
			url: "/dashboard/category/"+cat[1],

			success: function(result){
            	$("#edit-form").html(result);
        	},

        	error: function(){
				alert( "Internal Error" );
			}
        });

	});

	$("#is-del-cat-dialog").dialog({
	   autoOpen: false,
	   dialogClass: "no-close",
	   modal: true,
	   buttons : {
	        "Confirm" : function() {
	            alert("You have confirmed!");
	        },
	        "Cancel" : function() {
	          $(this).dialog("close");
	        }
	      }
	    });


	$( "button[id^='del_btn']" ).button({
		icons: { primary: "ui-icon-closethick" },
		text: false
	});

	$("button[id^='del_btn']").on("click", function(e) {
	    e.preventDefault();
	    $("#is-del-cat-dialog").dialog("open");
	});
	$("button[id^='del_btn']").addClass( "del-btn" );


});

</script>
@stop