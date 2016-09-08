@extends('layouts.app')

@section('content')
 <div class="card">
 	<div class="card-header">
 		
 	</div>
 	<div class="card-body card-padding">
	<div class="table-responsive">
	  <table id="data-table-command" class="table table-striped table-vmiddle">
	      <thead>
	        <tr>
	            <th data-column-id="id" data-identifier="true" data-type="numeric" data-visible="false">rowid</th>
	            <th data-column-id="id"  data-type="numeric">ID</th>
	            <th data-column-id="name">Name</th>
	            <th data-column-id="email">Email</th>
	            <th data-column-id="commands" data-formatter="commands" data-sortable="false">Commands</th>
	        </tr>
	      </thead>
	      <tbody>
	        <?php
	            $index = 0;
	           for ($i= 0; $i < count($users) ; $i++) { 
	            $index++;
	              echo "<tr>";
	              echo "<td>" . $users[$i]['id'] . "</td>";
	              echo "<td>" . $index. "</td>";
	              echo "<td>" . $users[$i]['name']. "</td>";
	          	  echo "<td>" . $users[$i]['email']. "</td>";
	              echo "</tr>";
	           }
	        ?>
	      </tbody>
	    </table>
	</div>
	</div>
	</div>

	<script type="text/javascript">
	  ;(function($) {
	      $(document).ready(function() {
	          var grid = $("#data-table-command").bootgrid({
	          caseSensitive: false,
	          css: {
	              icon: 'zmdi icon',
	              iconColumns: 'zmdi-view-module',
	              iconDown: 'zmdi-expand-more',
	              iconRefresh: 'zmdi-refresh',
	              iconUp: 'zmdi-expand-less'
	          },
	            selection: true,
	            multiSelect: true,
	            rowSelect: true,
	            keepSelection: true,
	          formatters: {
	              commands: function (column, row)
	              {
	                  return "<button type=\"button\" class=\"btn btn-icon command-edit waves-effect waves-circle\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + "<button type=\"button\" class=\"btn btn-icon command-view waves-effect waves-circle\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-view-toc zmdi-hc-fw\"></span></button>";
	              }
	          },
	          rowSelect: true,
	          selection: true,
	          }).
	          on("loaded.rs.jquery.bootgrid", function(e){
	              grid.find(".command-edit").on("click", function(e)
	              {
	                var id = $(this).data('row-id');

	                if(id != undefined) {
	                  console.log(id);
	                }
	             });
	          }).end().find(".command-view").on("click", function(e){
	              id = $(this).data('row-id');

	              if(id != undefined)
	              {

	              }
	         });
	      });
	  })(jQuery);
	</script>

@endsection


