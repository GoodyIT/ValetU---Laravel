<div class="pm-body clearfix m-l-20  m-b-20">
  <div class="pmb-block">
    <div class="pmbb-header">
      <h2><i class="zmdi zmdi-equalizer m-r-5"></i> Information (Add New Address) <span class="search-required c-red f-12"></span> </h2>
    </div>
    <div class="pmbb-body p-l-30">
      <div class="pmbb-view" id="app">
        <form id="testForm" class="form-horizontal" method="POST" action="{{ url('home/store') }}" role="form">
          {{ csrf_field() }}
        <div class="row">
          <div class="col-xs-5 m-l-15">
            <div class="title form-group has-feedback">
                <label for="title" class="control-label">Title</label>
                <div class="fg-line">
                    <input type="text" class="form-control" name="title" id="title" data-error="required">
                </div>
                <div class="help-block with-errors c-red"></div>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>                       
          </div>

          <div class="col-xs-5 m-l-15">
            <div class="type form-group has-feedback ">
              <label for="type" class="control-label">type</label>
              <div class="fg-line">
                <input type="text" class="form-control" name="type" id="type" data-error="required">
              </div>
              <div class="help-block with-errors c-red"></div>
              <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>  
          </div>
        </div>
     
        <!-- Hidden Fields -->
        <input type="hidden" class="dl-address" name="address">
        <input type="hidden" class="dl-city" name="city">
        <input type="hidden" class="dl-state" name="state">
        <input type="hidden" class="dl-country" name="country">
        <input type="hidden" class="dl-zipcode" name="zipcode">
        <input type="hidden" class="dl-latitude" name="latitude">
        <input type="hidden" class="dl-longitude" name="longitude">
        <div class="row ">
            <div class="col-xs-5 m-l-15">
                <dl class="dl-horizontal">
                    <dt>Address</dt>
                    <dd class="dl-address"> </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>City</dt>
                    <dd class="dl-city"> </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>State</dt>
                    <dd class="dl-state"></dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>Country</dt>
                    <dd class="dl-country"></dd>
                </dl>
            </div>

            <div class="col-xs-5 m-l-15">
                <dl class="dl-horizontal">
                    <dt>Zip Code</dt>
                    <dd class="dl-zipcode"></dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>Latitude</dt>
                    <dd class="dl-latitude"></dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>Longitude</dt>
                    <dd class="dl-longitude"></dd>
                </dl>
            </div>
        </div>
        <button type="submit" class="add btn btn-primary btn-sm m-t-10 m-l-20">Add a Address</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
<script>
    var keys = ["title", "type"];
    var searchKeys = [ "address", "city", "state", "country", "zipcode", "langitude", "longitude"];
    $(document).ready(function() {
        $(".add").click(function(e){
            e.preventDefault();
            $form = $("#testForm");
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': "{!! csrf_token() !!}",
                }
            });

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(),
                success: function(result) {
                    window.location.href = "{{ url('home') }}";
                },
                error: function(data){
                    var errors = data.responseJSON;
                    // console.log(errors);

                    if ($('.search-required').hasClass('has-error')) {
                        $('.search-required').removeClass('has-error');
                        $('.search-required').html('');
                    }
                    $.each(keys, function(index, value){
                        if($("."+keys[index]).hasClass('has-error')){
                            $("."+keys[index]).removeClass('has-error')
                            $("."+keys[index]).children(".help-block").html("");
                        }
                    });

                    $.each(errors, function(key, value) {
                        // console.log(key + " " + value[0]);
                        $("."+key).addClass('has-error');
                        $("."+key).children(".help-block").html(value[0]);
                        if($.inArray(key, searchKeys)) {
                            $('.search-required').addClass('has-error'); 
                            $('.search-required').html('Please search an address to add'); 
                        }
                    });
                }
            });
        });
    });
</script> 