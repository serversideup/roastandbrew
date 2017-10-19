<style>

</style>

<template>
  <div class="page">
    <form>
      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Name
              <input type="text" placeholder="Cafe name" v-model="name">
            </label>
            <span class="validation" v-show="!validations.name.is_valid">{{ validations.name.text }}</span>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <label>Address
              <input type="text" placeholder="Address" v-model="address">
            </label>
            <span class="validation" v-show="!validations.address.is_valid">{{ validations.address.text }}</span>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <label>City
              <input type="text" placeholder="City" v-model="city">
            </label>
            <span class="validation" v-show="!validations.city.is_valid">{{ validations.city.text }}</span>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <label>State
              <input type="text" placeholder="State" v-model="state">
            </label>
            <span class="validation" v-show="!validations.state.is_valid">{{ validations.state.text }}</span>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <label>Zip
              <input type="text" placeholder="Zip" v-model="zip">
            </label>
            <span class="validation" v-show="!validations.zip.is_valid">{{ validations.zip.text }}</span>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <a class="button" v-on:click="submitNewCafe()">Add Cafe</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
  export default {
    data(){
      return {
        name: '',
        address: '',
        city: '',
        state: '',
        zip: '',
        validations: {
          name: {
            is_valid: true,
            text: ''
          },
          address: {
            is_valid: true,
            text: ''
          },
          city: {
            is_valid: true,
            text: ''
          },
          state: {
            is_valid: true,
            text: ''
          },
          zip: {
            is_valid: true,
            text: ''
          }
        }
      }
    },

    methods: {
      submitNewCafe(){
        if( this.validateNewCafe() ){
          this.$store.dispatch( 'addCafe', {
  					name: this.name,
            address: this.address,
            city: this.city,
            state: this.state,
            zip: this.zip
  				});
        }
      },

      validateNewCafe(){
        let validNewCafeForm = true;

        /*
          Ensure a name has been entered
        */
        if( this.name.trim() == '' ){
          validNewCafeForm = false;
          this.validations.name.is_valid = false;
          this.validations.name.text = 'Please enter a name for the new cafe!';
        }else{
          this.validations.name.is_valid = true;
          this.validations.name.text = '';
        }

        /*
          Ensure an address has been entered
        */
        if( this.name.trim() == '' ){
          validNewCafeForm = false;
          this.validations.address.is_valid = false;
          this.validations.address.text = 'Please enter an address for the new cafe!';
        }else{
          this.validations.address.is_valid = true;
          this.validations.address.text = '';
        }

        /*
          Ensure a city has been entered
        */
        if( this.city.trim() == '' ){
          validNewCafeForm = false;
          this.validations.city.is_valid = false;
          this.validations.city.text = 'Please enter a city for the new cafe!';
        }else{
          this.validations.city.is_valid = true;
          this.validations.city.text = '';
        }

        /*
          Ensure a state has been entered
        */
        if( this.state.trim() == '' ){
          validNewCafeForm = false;
          this.validations.state.is_valid = false;
          this.validations.state.text = 'Please enter a state for the new cafe!';
        }else{
          this.validations.state.is_valid = true;
          this.validations.state.text = '';
        }

        /*
          Ensure a zip has been entered
        */
        if( this.zip.trim() == '' || !this.zip.match(/(^\d{5}$)/) ){
          validNewCafeForm = false;
          this.validations.zip.is_valid = false;
          this.validations.zip.text = 'Please enter a valid zip code for the new cafe!';
        }else{
          this.validations.zip.is_valid = true;
          this.validations.zip.text = '';
        }

        return validNewCafeForm;
      }
    }
  }
</script>
