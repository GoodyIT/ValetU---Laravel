  <?php
      echo Form::open(['url'=>'/uber/v1/savereview','files'=>'true']);
      echo 'Please Select a File to Upload ';
      echo '<br>';
      echo Form::file('image');
      echo '<br>';
      echo Form::text('star','1');
      echo '<br/>';
      echo Form::text('review','review');
      echo '<br/>';
      echo Form::text('token','1606f461-10bb-4ca5-ada5-edf9e0b56b24');
      echo '<br/>';
      echo Form::text('parkinglot_id','1');
      echo '<br/>';
      echo Form::submit('Upload File');
      echo '<br>';
      echo Form::close();
     ?>