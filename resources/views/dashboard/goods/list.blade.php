@extends('layouts.middle')

@section('title')
{{ @trans('prompts.goods') }}
@stop

@section('buttons')
	@include('dashboard/blocks/buttons')
@stop


@section('content')


<div class="jumbotron j-tbl">

<table id="goodstable">
	<thead>
	<tr>

		<th rowspan="2">{{ @trans('prompts.name') }}</th>
		<th rowspan="2">{{ @trans('prompts.article') }}</th>

		<th colspan="2">{{ @trans('prompts.price') }}</th>
		<th colspan="3">{{ @trans('prompts.quantity') }}</th>
	</tr>
	<tr>
		<th>{{ @trans('prompts.wholesale') }}</th>
		<th>{{ @trans('prompts.retail') }}</th>
		<th>{{ @trans('prompts.in_pack') }}</th>
		<th>{{ @trans('prompts.packs') }}</th>
		<th>{{ @trans('prompts.assort') }}</th>
	</tr>
	</thead>

	<tfoot>
	<tr>
		<th><button class="ind-search-btn"></button><input class="form-control f-inp" type="text" placeholder="{{ @trans('prompts.column_search') }}" /><button class="ind-clean-btn"></th>
		<th><button class="ind-search-btn"></button><input class="form-control f-inp" type="text" placeholder="{{ @trans('prompts.column_search') }}" /><button class="ind-clean-btn"></th>
		<th colspan="5">&nbsp;</th>
	</tr>
	</tfoot>

</table>
</div>

@stop

@section('js_extra')
<script type="text/javascript">
$(document).ready(function(){
	var	goods_table=

	$('#goodstable').DataTable( {
		"processing": true,
		"serverSide": true,

		"columnDefs": [
			{ "searchable": false, "targets": [ 2,3,4,5,6 ] }
		],

		"columns":[
		   {"name":"name"},
		   {"name":"article"},
		   {"name":"w_price"},
		   {"name":"r_price"},
		   {"name":"in_pack"},
		   {"name":"packs"},
		   {"name":"assort"}
		],

		"language": tbl_prompts,

		"ajax": "/dashboard/goodstable"
	});

	//Set input CSS styles
    $('#goodstable_filter input').addClass('form-control');
    $('#goodstable_length select').addClass('form-control');
    $("#goodstable_filter input").attr("placeholder", "{{ @trans('prompts.search') }}");


	//Main search button
    $("#goodstable_filter").prepend("<button id='search_btn'></button>");
    $("#search_btn").button({
		icons: { primary: "ui-icon-search" },
		text: false
	});
	$('#search_btn').on('click', function(e) {
		goods_table.search($('#goodstable_filter input').val()).draw();
	});

	//Main clean button
	$("#goodstable_filter").append("<button id='clean_btn'></button>");
    $("#clean_btn").button({
		icons: { primary: "ui-icon-cancel" },
		text: false
	});
	$('#clean_btn').on('click', function(e) {
		$('#goodstable_filter input').val("");
		goods_table.search($('#goodstable_filter input').val()).draw();
	});

	//Change main search input handler
	$('#goodstable_filter input').unbind();
	$('#goodstable_filter input').on('keyup change', function(e) {
		(e.keyCode == 13) ? goods_table.search($(this).val()).draw():null;
	});


	//Set handlers for individual search inputs
    goods_table.columns().every( function () {
        var col_obj = this
        ,inp_obj	= $( 'input', this.footer())
        ;

        inp_obj.on( 'keyup change', function(e){
        	(e.keyCode == 13)
            	? col_obj.search( $(this).val() ).draw():null;
        });

        $('.ind-clean-btn', this.footer()).on( 'click', function(e){
        	inp_obj.val("");
        	col_obj.search( inp_obj.val()).draw();
        });
});

	//Individual search buttons style
    $(".ind-search-btn").button({
		icons: { primary: "ui-icon-search" },
		text: false
	});

	//Individual clean buttons style
    $(".ind-clean-btn").button({
		icons: { primary: "ui-icon-cancel" },
		text: false
	});



    $(".ind-search-btn").on( 'click', function(e){
		var
			col_0=goods_table.column(0)
			,col_1=goods_table.column(1);

    	col_0.search( $('input',col_0.footer()).val());
    	col_1.search( $('input',col_1.footer()).val());

    	goods_table.draw();
    });

});


</script>
@stop
