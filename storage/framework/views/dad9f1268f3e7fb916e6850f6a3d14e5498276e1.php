<div class="table-responsive">
  <table id="data-table-command" class="table table-striped table-vmiddle">
      <thead>
        <tr>
            <th data-column-id="id" data-identifier="true" data-type="numeric" data-visible="false">rowid</th>
            <th data-column-id="id"  data-type="numeric">ID</th>
            <th data-column-id="title">Title</th>
            <th data-column-id="type">Type</th>
            <th data-column-id="address">Address</th>
            <th data-column-id="city">City</th>
            <th data-column-id="state">State</th>
            <th data-column-id="zipcode">Zip code</th>
            <th data-column-id="country">Country</th>
            <th data-column-id="latitude">Latitude</th>
            <th data-column-id="longitude">Longitude</th>
            <th data-column-id="commands" data-formatter="commands" data-sortable="false">Commands</th>
        </tr>
      </thead>
      <tbody>
        <?php
            $index = 0;
           for ($i= 0; $i < count($locations) ; $i++) { 
            $index++;
              echo "<tr>";
              echo "<td>" . $locations[$i]['id'] . "</td>";
              echo "<td>" . $index. "</td>";
              echo "<td>" . $locations[$i]['title']. "</td>";
              echo "<td>" . $locations[$i]['type'] . "</td>";
              echo "<td>" . $locations[$i]['address'] . "</td>";
              echo "<td>" . $locations[$i]['city'] . "</td>";
              echo "<td>" . $locations[$i]['state'] . "</td>";
              echo "<td>" . $locations[$i]['zipcode']  . "</td>";
              echo "<td>" . $locations[$i]['country']  . "</td>";
              echo "<td>" . $locations[$i]['latitude'] . "</td>";
              echo "<td>" . $locations[$i]['longitude']  . "</td>";
              echo "</tr>";
           }
        ?>
      </tbody>
    </table>
</div>

<!-- <?php echo e(isset($location) ? ($location) : 'Default'); ?> -->
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
                  return "<button type=\"button\" class=\"btn btn-icon command-edit waves-effect waves-circle\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-edit\"></span></button> " + "<button type=\"button\" class=\"btn btn-icon command-delete waves-effect waves-circle\" data-row-id=\"" + row.rowid + "\"><span class=\"zmdi zmdi-delete\"></span></button>";
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
          }).end().find(".command-delete").on("click", function(e){
              id = $(this).data('row-id');

              if(id != undefined)
              {

              }
         });
      });
  })(jQuery);
</script>