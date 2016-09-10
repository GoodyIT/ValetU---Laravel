

<div id="app">
<form @submit.prevent="submitForm" class="col-md-4 col-md-offset-4" action="/home/store" method="post">
    <h1>Create New Article</h1>
    <hr>

    <?php echo csrf_field(); ?>


     <div class="form-group">
        <input class="form-control title" type="text" name="title" placeholder="Title" v-model="formInputs.title">
        <span v-if="formErrors['title']" class="error">{{ formErrors['title'] }}</span>
    </div>

    <div class="form-group">
        <textarea class="form-control type" name="type" placeholder="Content" v-model="formInputs.type"></textarea>
        <span v-if="formErrors['type']" class="error">{{ formErrors['type'] }}</span>
    </div>

<!-- Hidden Fields -->
        <!-- Hidden Fields -->
        <div class="form-group">
            <input id="input-address" type="text" class="form-control address" name="address" v-model="formInputs.address">
             <span v-if="formErrors['address']" class="error">{{ formErrors['address'] }}</span>
        </div>
      
        <input id="input-city" type="text" class="form-control" name="city" v-model="formInputs.city" >
        <div v-if="formErrors['city']" class="help-block with-errors">{{ formErrors['city'] }}</div>
       
       
        <input id="input-state" type="text" class="form-control" name="state" v-model="formInputs.state" >
        <div v-if="formErrors['state']" class="help-block with-errors">{{ formErrors['state'] }}</div>
        

        <input id="input-zipcode" v-model="formInputs.zipcode" type="text" class="form-control" name="zipcode">
        <div v-if="formErrors['zipcode']" class="help-block with-errors">{{ formErrors['zipcode'] }}</div>
       

        <input id="input-country" type="text" class="form-control" name="country" v-model="formInputs.country" >
        <div v-if="formErrors['country']" class="help-block with-errors">{{ formErrors['country'] }}</div>
       

        <input id="input-latitude" type="text" class="form-control" name="latitude" v-model="formInputs.latitude" >
        <div v-if="formErrors['latitude']" class="help-block with-errors">{{ formErrors['latitude'] }}</div>
       

        <input id="input-longitude" type="text" class="form-control" name="longitude" v-model="formInputs.longitude" >
        <div v-if="formErrors['longitude']" class="help-block with-errors">{{ formErrors['longitude'] }}</div>
       

        <button type="submit" class="btn btn-primary btn-sm m-t-10 m-l-20 waves-effect">Add a Address</button>
        </form>

     </div>

       <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.14/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.6.1/vue-resource.min.js"></script>
  <script>
    new Vue({
    el: '#app',

    data: {
        formInputs: {},
        formErrors: {}
    },

    methods: {
        submitForm: function(e) {
            var form = e.srcElement;
            var action = form.action;
            var csrfToken = form.querySelector('input[name="_token"]').value;
            
            this.$http.post(action, this.formInputs, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(function() {
                form.submit();
            })
            .catch(function (data, status, request) {
                this.formErrors = data.data;
            });
        }
    }
});
</script>

