<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#admin-cities{
    a.add-city{
      display: block;
      width: 150px;
      color: white;
      background-color: #CCC;
      text-align: center;
      border-radius: 5px;
      float: right;
      height: 45px;
      line-height: 45px;
    }

    div.city-listing{
      padding-top: 10px;
      padding-bottom: 10px;
      border-bottom: 1px solid black;
    }

    div.new-city-modal{
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: rgba( 0, 0, 0, .6 );
      z-index: 99999;

      div.modal-box{
        width: 100%;
        max-width: 530px;
        min-width: 320px;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        -webkit-box-shadow: 0 1px 3px rgba(50,50,50,0.08);
        box-shadow: 0 1px 3px rgba(50,50,50,0.08);
        -webkit-border-radius: 4px;
        border-radius: 4px;
        font-size: 16px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-height: 500px;
        overflow-y: auto;

        label{
          font-weight: bold;
        }

        a.add-city-button{
          display: block;
          width: 150px;
          color: white;
          background-color: #CCC;
          text-align: center;
          border-radius: 5px;
          margin-top: 20px;
          height: 45px;
          line-height: 45px;
        }
      }
    }
  }
</style>

<template>
  <div id="admin-cities">
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-8 medium-8 cell">
          <h3 class="page-header">Cities</h3>
        </div>
        <div class="large-4 medium-4 cell">
          <a class="add-city" v-on:click="showNewCityModal = true">Add City</a>
        </div>
      </div>
    </div>

    <div class="grid-container">
      <div class="grid-x list-header">
        <div class="large-3 medium-3 cell">
          Name
        </div>
        <div class="large-3 medium-3 cell">
          State
        </div>
        <div class="large-3 medium-3 cell">
          Country
        </div>
        <div class="large-3 medium-3 cell">

        </div>
      </div>
      <div class="grid-x listing" v-for="city in cities">
        <div class="large-3 medium-3 cell">
          {{ city.name }}
        </div>
        <div class="large-3 medium-3 cell">
          {{ city.state }}
        </div>
        <div class="large-3 medium-3 cell">
          {{ city.country }}
        </div>
        <div class="large-3 medium-3 cell">
          <router-link :to="{ name: 'admin-city', params: { 'id': city.id} }">Edit</router-link>
        </div>
      </div>
    </div>

    <div class="new-city-modal" v-show="showNewCityModal" v-on:click="hideNewCityModal()">
      <div class="modal-box" v-on:click.stop="">
        <div class="grid-x">
          <div class="large-12 medium-12 cell">
            <label>Name</label>
            <input type="text" id="city-name" v-model="name"/>
            <span class="validation" v-show="!validations.name">Please enter a city name!</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 cell">
            <label>State</label>
            <select v-model="state" v-bind:class="{'invalid' : !validations.state }">
              <option value=""></option>
              <option value="AL">Alabama</option>
              <option value="AK">Alaska</option>
              <option value="AZ">Arizona</option>
              <option value="AR">Arkansas</option>
              <option value="CA">California</option>
              <option value="CO">Colorado</option>
              <option value="CT">Connecticut</option>
              <option value="DE">Delaware</option>
              <option value="DC">District Of Columbia</option>
              <option value="FL">Florida</option>
              <option value="GA">Georgia</option>
              <option value="HI">Hawaii</option>
              <option value="ID">Idaho</option>
              <option value="IL">Illinois</option>
              <option value="IN">Indiana</option>
              <option value="IA">Iowa</option>
              <option value="KS">Kansas</option>
              <option value="KY">Kentucky</option>
              <option value="LA">Louisiana</option>
              <option value="ME">Maine</option>
              <option value="MD">Maryland</option>
              <option value="MA">Massachusetts</option>
              <option value="MI">Michigan</option>
              <option value="MN">Minnesota</option>
              <option value="MS">Mississippi</option>
              <option value="MO">Missouri</option>
              <option value="MT">Montana</option>
              <option value="NE">Nebraska</option>
              <option value="NV">Nevada</option>
              <option value="NH">New Hampshire</option>
              <option value="NJ">New Jersey</option>
              <option value="NM">New Mexico</option>
              <option value="NY">New York</option>
              <option value="NC">North Carolina</option>
              <option value="ND">North Dakota</option>
              <option value="OH">Ohio</option>
              <option value="OK">Oklahoma</option>
              <option value="OR">Oregon</option>
              <option value="PA">Pennsylvania</option>
              <option value="RI">Rhode Island</option>
              <option value="SC">South Carolina</option>
              <option value="SD">South Dakota</option>
              <option value="TN">Tennessee</option>
              <option value="TX">Texas</option>
              <option value="UT">Utah</option>
              <option value="VT">Vermont</option>
              <option value="VA">Virginia</option>
              <option value="WA">Washington</option>
              <option value="WV">West Virginia</option>
              <option value="WI">Wisconsin</option>
              <option value="WY">Wyoming</option>
            </select>
            <div class="validation" v-show="!validations.name">Please select a state!</div>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 cell">
            <label>Country</label>
            <input type="text" id="country" v-model="country"/>
            <span class="validation" v-show="!validations.country">Please enter a country!</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 cell">
            <label>Latitude</label>
            <input type="text" id="latitude" v-model="latitude"/>
            <span class="validation" v-show="!validations.latitude">Please enter a latitude!</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 cell">
            <label>Longitude</label>
            <input type="text" id="longitude" v-model="longitude"/>
            <span class="validation" v-show="!validations.longitude">Please enter a longitude!</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 cell">
            <label>Radius</label>
            <input type="text" id="country" v-model="radius"/>
            <span class="validation" v-show="!validations.radius">Please enter a radius!</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 cell">
            <a class="add-city-button" v-on:click="addCity()">Add City</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  /*
    Imports the event bus to emit events.
  */
  import { EventBus } from '../../event-bus.js';

  export default {
    /*
      Defines the data used by the cities component.
    */
    data(){
      return {
        showNewCityModal: false,

        name: '',
        state: '',
        country: '',
        radius: '',
        latitude: '',
        longitude: '',

        validations: {
          name: true,
          state: true,
          country: true,
          radius: true,
          latitude: true,
          longitude: true
        }
      }
    },

    /*
      When created load the admin cities.
    */
    created(){
      this.$store.dispatch('loadAdminCities');
    },

    /*
      Defines the computed variables used by the component.
    */
    computed: {
      /*
        Gets the cities from the Vuex data store.
      */
      cities(){
        return this.$store.getters.getAdminCities;
      },

      /*
        Gets the cities add status from the Vuex data store.
      */
      cityAddStatus(){
        return this.$store.getters.getAdminCityAddStatus;
      }
    },

    /*
      Defines what to watch in the component.
    */
    watch: {
      /*
        If the city add status is successful, hide the modal
        and show success message.
      */
      'cityAddStatus': function(){
        if( this.cityAddStatus == 2 ){
          this.clearForm();
          EventBus.$emit('show-success', {
            notification: 'City added successfully!'
          });
          this.showNewCityModal = false;
        }
      }
    },

    /*
      Define the mounted lifecycle hook.
    */
    mounted(){
      /*
        Gets the autocomplete element and sets it up with Google places autocomplete.
      */
      this.autocomplete = document.getElementById('city-name');
      let googleMapsAutocomplete = new google.maps.places.Autocomplete( this.autocomplete );

      /*
        Listen to when the place has changed.
      */
      googleMapsAutocomplete.addListener( 'place_changed', function(){
        let place = googleMapsAutocomplete.getPlace();

        /*
          Find the address we need in the address components.
        */
        for (var i = 0; i < place.address_components.length; i++) {
          let type = place.address_components[i].types[0];

          /*
            Switch the type of the address components and assign it to the
            corresponding variable.
          */
          switch( type ){
            case 'locality':
              this.name = place.address_components[i].short_name;
            break;
            case 'administrative_area_level_1':
              this.state = place.address_components[i].short_name;
            break;
            case 'country':
              this.country = place.address_components[i].short_name;
            break;
          }
        }

        /*
          Gets the latitude and longitude of the address.
        */
        this.latitude = place.geometry.location.lat();
        this.longitude = place.geometry.location.lng();

      }.bind(this));
    },

    /*
      Defines the methods used by the component.
    */
    methods: {
      /*
        Add a city.
      */
      addCity(){
        if( this.validateNewCity() ){
          this.$store.dispatch( 'addAdminCity', {
            name: this.name,
            state: this.state,
            country: this.country,
            radius: this.radius,
            latitude: this.latitude,
            longitude: this.longitude
          });
        }
      },

      /*
        Validate a new city.
      */
      validateNewCity(){
        let validNewCityForm = true;

        if( this.name.trim() == '' ){
          validNewCityForm = false;
          this.validations.name = false;
        }else{
          this.validations.name = true;
        }

        if( this.state == '' ){
          validNewCityForm = false;
          this.validations.state = false;
        }else{
          this.validations.state = true;
        }

        if( this.country == '' ){
          validNewCityForm = false;
          this.validations.country = false;
        }else{
          this.validations.country = true;
        }

        if( this.radius == '' ){
          validNewCityForm = false;
          this.validations.radius = false;
        }else{
          this.validations.radius = true;
        }

        if( this.latitude == '' ){
          validNewCityForm = false;
          this.validations.latitude = false;
        }else{
          this.validations.latitude = true;
        }

        if( this.longitude == '' ){
          validNewCityForm = false;
          this.validations.longitude = false;
        }else{
          this.validations.longitude = true;
        }

        return validNewCityForm;
      },

      /*
        Hide the new city modal.
      */
      hideNewCityModal(){
        this.showNewCityModal = false;

        this.clearForm();
      },

      /*
        Clear the form.
      */
      clearForm(){
        this.name = '';
        this.state = '';
        this.country = '';
        this.radius = '';
        this.latitude = '';
        this.longitude = '';


        this.validations.name = true;
        this.validations.state = true;
        this.validations.country = true;
        this.validations.radius = true;
        this.validations.latitude = true;
        this.validations.longitude = true;
      }
    }

  }
</script>
