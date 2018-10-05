<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  a.save-edits{
    display: inline-block;
    width: 150px;
    color: white;
    background-color: #CCC;
    text-align: center;
    border-radius: 5px;
    margin-top: 20px;
    height: 45px;
    line-height: 45px;
    margin-right: 10px;
    margin-bottom: 20px;
  }

  a.delete-city{
    display: inline-block;
    width: 150px;
    color: white;
    background-color: #E8635F;
    text-align: center;
    border-radius: 5px;
    margin-top: 20px;
    height: 45px;
    line-height: 45px;
    margin-right: 10px;
    margin-bottom: 20px;
  }
</style>

<template>
  <div id="admin-city">
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <h3 class="page-header">
            <router-link :to="{ name: 'admin-cities'}">Cities</router-link> >
            {{ city.name }}
          </h3>
        </div>
      </div>
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Name</label>
          <input type="text" v-model="name"/>
          <span class="validation" v-show="!validations.name">Please enter a city name!</span>
        </div>
      </div>
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
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
        <div class="large-8 medium-12 cell">
          <label>Country</label>
          <input type="text" v-model="country"/>
          <span class="validation" v-show="!validations.country">Please enter a country!</span>
        </div>
      </div>
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Latitude</label>
          <input type="text" v-model="latitude"/>
          <span class="validation" v-show="!validations.latitude">Please enter a latitude!</span>
        </div>
      </div>
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Longitude</label>
          <input type="text" v-model="longitude"/>
          <span class="validation" v-show="!validations.longitude">Please enter a longitude!</span>
        </div>
      </div>
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Radius</label>
          <input type="text" v-model="radius"/>
          <span class="validation" v-show="!validations.radius">Please enter a radius!</span>
        </div>
      </div>
      <div class="grid-x">
        <div class="large-12 medium-12 cell">
          <a class="save-edits" v-on:click="saveEdits()">Update City</a>
          <a class="delete-city" v-on:click="deleteCity()">Delete City</a>
        </div>
      </div>
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <h3 class="page-header">Cafes</h3>
        </div>
      </div>
      <div class="grid-x list-header">
        <div class="large-3 medium-3 cell">
          Company
        </div>
        <div class="large-3 medium-3 cell">
          Cafe
        </div>
        <div class="large-3 medium-3 cell">
          Address
        </div>
        <div class="large-3 medium-3 cell">

        </div>
      </div>
      <div class="grid-x listing" v-for="cafe in city.cafes">
        <div class="large-3 medium-3 cell">
          {{ cafe.company.name }}
        </div>
        <div class="large-3 medium-3 cell">
          {{ cafe.location_name }}
        </div>
        <div class="large-3 medium-3 cell">
          {{ cafe.address }}
        </div>
        <div class="large-3 medium-3 cell">
          <router-link :to="{ name: 'admin-cafe', params: { id: cafe.company.id, cafeID: cafe.id } }">Edit</router-link>
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
      Defines the data used by the component.
    */
    data(){
      return {
        name: '',
        state: '',
        country: '',
        latitude: '',
        longitude: '',
        radius: '',

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
      When created load the admin city.
    */
    created(){
      this.$store.dispatch( 'loadAdminCity', { id: this.$route.params.id } );
    },

    /*
      Defines the computed data used by the component.
    */
    computed: {
      /*
        Imports the city from the Vuex data store.
      */
      city(){
        return this.$store.getters.getAdminCity;
      },

      /*
        Imports the city load status from the Vuex data store.
      */
      cityLoadStatus(){
        return this.$store.getters.getAdminCityLoadStatus;
      },

      /*
        Imports the city edit status from the Vuex data store.
      */
      cityEditStatus(){
        return this.$store.getters.getAdminCityEditStatus;
      },

      /*
        Imports the city delete status from the Vuex data store.
      */
      cityDeleteStatus(){
        return this.$store.getters.getAdminCityDeleteStatus;
      }
    },

    /*
      Defines what to watch in the component.
    */
    watch: {
      /*
        When the city is loaded, copy the data to the model.
      */
      'cityLoadStatus': function(){
        this.syncCityToModel();
      },

      /*
        When the city is deleted, show a success message
        and navigate back to the cities page.
      */
      'cityDeleteStatus': function(){
        if( this.cityDeleteStatus == 2 ){
          EventBus.$emit('show-success', {
            notification: this.name+' deleted successfully!'
          });

          this.$router.push({ name: 'admin-cities' });
        }
      },

      /*
        When the city has been edited show a success message.
      */
      'cityEditStatus': function(){
        if( this.cityEditStatus == 2 ){
          EventBus.$emit('show-success', {
            notification: this.name+' updated successfully!'
          });
        }
      }
    },

    /*
      Defines the methods used by the component.
    */
    methods: {
      /*
        Sync the city to model.
      */
      syncCityToModel(){
        this.name       = this.city.name;
        this.state      = this.city.state;
        this.country    = this.city.country;
        this.latitude   = this.city.latitude;
        this.longitude  = this.city.longitude;
        this.radius     = this.city.radius;
      },

      /*
        Save the city edits.
      */
      saveEdits(){
        if( this.validateEditCity() ){
          this.$store.dispatch( 'updateAdminCity', {
            id: this.city.id,
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
        Delete a city.
      */
      deleteCity(){
        this.$store.dispatch( 'deleteAdminCity', {
          id: this.city.id
        });
      },

      /*
        Validate editing a city.
      */
      validateEditCity(){
        let validEditCityForm = true;

        if( this.name.trim() == '' ){
          validEditCityForm = false;
          this.validations.name = false;
        }else{
          this.validations.name = true;
        }

        if( this.state == '' ){
          validEditCityForm = false;
          this.validations.state = false;
        }else{
          this.validations.state = true;
        }

        if( this.country == '' ){
          validEditCityForm = false;
          this.validations.country = false;
        }else{
          this.validations.country = true;
        }

        if( this.radius == '' ){
          validEditCityForm = false;
          this.validations.radius = false;
        }else{
          this.validations.radius = true;
        }

        if( this.latitude == '' ){
          validEditCityForm = false;
          this.validations.latitude = false;
        }else{
          this.validations.latitude = true;
        }

        if( this.longitude == '' ){
          validEditCityForm = false;
          this.validations.longitude = false;
        }else{
          this.validations.longitude = true;
        }

        return validEditCityForm;
      }
    }
  }
</script>
