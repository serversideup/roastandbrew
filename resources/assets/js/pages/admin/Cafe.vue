<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#admin-cafe{
    label{
      font-weight: bold;
      color: black;
      font-size: 16px;
      margin-top: 15px;
    }

    a.update-cafe{
      display: block;
      width: 150px;
      color: white;
      background-color: #CCC;
      text-align: center;
      border-radius: 5px;
      margin-top: 20px;
      height: 45px;
      line-height: 45px;
      margin-bottom: 100px;
    }
  }
</style>

<template>
  <div id="admin-cafe">
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <h3 class="page-header">
            <router-link :to="{ name: 'admin-companies'}">Companies</router-link> >
            <router-link :to="{ name: 'admin-company', params: { id: this.$route.params.id } }">{{ company.name }}</router-link> >
            {{ cafe.location_name != '' ? cafe.location_name : company.name+' at '+cafe.address+' '+cafe.city+' '+cafe.state }}
          </h3>
        </div>
      </div>

      <div class="grid-container">
        <div class="grid-x admin-tabs">
          <div class="tab" v-on:click="tab = 'information'" v-bind:class="{'active': tab == 'information'}">
            Information
          </div>
          <div class="tab" v-on:click="tab = 'activity'" v-bind:class="{'active': tab == 'activity'}">
            Activity
          </div>
          <div class="tab" v-on:click="tab = 'history'" v-bind:class="{'active': tab == 'history'}">
            History
          </div>
        </div>
      </div>

      <div class="grid-container" v-show="tab == 'activity'">
        <div class="grid-x">
          <div class="large-8 medium-12 cell">
            <label>Likes</label>
            {{ cafe.likes_count }} likes
          </div>
        </div>
      </div>

      <div class="grid-container" v-show="tab == 'information'">
        <div class="grid-x">
          <div class="large-8 medium-12 cell">
            <label>Location Name</label>
            <input type="text" v-model="location_name"/>
          </div>
        </div>

        <div class="grid-x">
          <div class="large-8 medium-12 cell">
            <label>Address</label>
            <input type="text" v-model="address"/>
            <span class="validation" v-show="!validations.address">Please enter a valid address</span>
          </div>
        </div>

        <div class="grid-x">
          <div class="large-8 medium-12 cell">
            <label>City</label>
            <input type="text" v-model="city"/>
            <span class="validation" v-show="!validations.city">Please enter a valid city</span>
          </div>
        </div>

        <div class="grid-x">
          <div class="large-8 medium-12 cell">
            <label>Bound To City</label>
            <select id="bound-to-city" v-model="boundToCity">
              <option value=""></option>
              <option v-for="city in cities" v-bind:value="city.id">{{ city.name }}</option>
            </select>
          </div>
        </div>

        <div class="grid-x">
          <div class="large-8 medium-12 cell">
            <label>State</label>
            <select v-model="state">
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
          </div>
        </div>

        <div class="grid-x">
          <div class="large-8 medium-12 cell">
            <label>Zip</label>
            <input type="text" v-model="zip"/>
            <span class="validation" v-show="!validations.zip">Please enter a valid zip</span>
          </div>
        </div>

        <div class="grid-x">
          <div class="large-8 medium-12 cell">
            <label>Matcha</label>
            <div class="drink-option option" v-on:click="matcha == 0 ? matcha = 1 : matcha = 0" v-bind:class="{'active': matcha == 1 }">
              <div class="option-container">
                <img v-bind:src="'/img/icons/matcha-latte.svg'" class="option-icon"/> <span class="option-name">Matcha</span>
              </div>
            </div>
          </div>
        </div>

        <div class="grid-x">
          <div class="large-8 medium-12 cell">
            <label>Tea Options</label>
            <div class="drink-option option" v-on:click="tea == 0 ? tea = 1 : tea = 0" v-bind:class="{'active': tea == 1 }">
              <div class="option-container">
                <img v-bind:src="'/img/icons/tea-bag.svg'" class="option-icon"/> <span class="option-name">Tea Options</span>
              </div>
            </div>
          </div>
        </div>

        <div class="grid-x">
          <div class="large-8 medium-12 small-12 cell">
            <label>Brew Methods</label>
            <div class="brew-method option" v-on:click="toggleSelectedBrewMethod( method.id )" v-for="method in brewMethods" v-bind:class="{'active': brewMethodsSelected.indexOf( method.id ) >= 0 }">
              <div class="option-container">
                <img v-bind:src="method.icon+'.svg'" class="option-icon"/> <span class="option-name">{{ method.method }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="grid-x">
          <div class="large-8 medium-12 cell">
            <label>Status</label>
            <select v-model="deleted">
              <option value="0">Active</option>
              <option value="1">Deleted</option>
            </select>
          </div>
        </div>

        <div class="grid-x">
          <div class="large-12 medium-12 cell">
            <a class="update-cafe" v-on:click="updateCafe()">Update Cafe</a>
          </div>
        </div>
      </div>

      <div class="grid-container" v-show="tab == 'history'">

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
      Defines the data used by the page.
    */
    data(){
      return {
        tab: 'information',

        location_name: '',
        address: '',
        city: '',
        state: '',
        zip: '',
        tea: '',
        matcha: '',
        brewMethodsSelected: [],
        boundToCity: '',
        deleted: 0,

        validations: {
          location_name: true,
          address: true,
          city: true,
          state: true,
          zip: true
        }
      }
    },

    /*
      Configures the component when created.
    */
    created(){
      this.$store.dispatch( 'loadAdminCompany', { id: this.$route.params.id } );
      this.$store.dispatch( 'loadAdminCafe', { company_id: this.$route.params.id, cafe_id: this.$route.params.cafeID } );
      this.$store.dispatch( 'loadAdminCities' );
    },

    /*
      Defines the computed properties used by the component.
    */
    computed: {
      /*
        Gets the brew methods from the Vuex store.
      */
      brewMethods(){
        return this.$store.getters.getBrewMethods;
      },

      /*
        Gets the company the cafe belongs to from the Vuex store.
      */
      company(){
        return this.$store.getters.getCompany;
      },

      /*
        Gets the cafe from the Vuex store.
      */
      cafe(){
        return this.$store.getters.getAdminCafe;
      },

      /*
        Gets the cafe load status from the Vuex store.
      */
      cafeLoadStatus(){
        return this.$store.getters.getAdminCafeLoadStatus;
      },

      /*
        Gets the cafe edit status from the Vuex store.
      */
      cafeEditStatus(){
        return this.$store.getters.getAdminCafeEditStatus;
      },

      /*
        Gets the cities from the Vuex store.
      */
      cities(){
        return this.$store.getters.getAdminCities;
      },

      /*
        Gets the cities load status from the Vuex store.
      */
      citiesLoadStatus(){
        return this.$store.getters.getAdminCitiesLoadStatus;
      }
    },

    /*
      Defines the watchers used by the page.
    */
    watch: {
      /*
        When the cafe has been loaded successfully, we sync the data
        to the local model for editing.
      */
      'cafeLoadStatus': function(){
        if( this.cafeLoadStatus == 2 ){
          this.syncCafeToModel();
        }
      },

      /*
        When the cafe has been updated successfuly, we sync the data
        to the local model for editing and display a notification.
      */
      'cafeEditStatus': function(){
        if( this.cafeEditStatus == 2 ){
          this.syncCafeToModel();

          EventBus.$emit('show-success', {
            notification: 'Cafe updated successfully!'
          });
        }
      }
    },

    /*
      Defines the methods used by the page.
    */
    methods: {
      /*
        Syncs the cafe data to the model data.
      */
      syncCafeToModel(){
        this.location_name = this.cafe.location_name;
        this.address = this.cafe.address;
        this.city = this.cafe.city;
        this.state = this.cafe.state;
        this.zip = this.cafe.zip;
        this.tea = this.cafe.tea;
        this.matcha = this.cafe.matcha;
        this.boundToCity = this.cafe.city_id;

        for( let i = 0; i < this.cafe.brew_methods.length; i++ ){
          this.brewMethodsSelected.push( this.cafe.brew_methods[i].id );
        }

        this.deleted = this.cafe.deleted;
      },

      /*
        Toggles the selected brew method
      */
      toggleSelectedBrewMethod( id ){
        if( this.brewMethodsSelected.indexOf( id ) >= 0 ){
          this.brewMethodsSelected.splice( this.brewMethodsSelected.indexOf( id ), 1 );
        }else{
          this.brewMethodsSelected.push( id );
        }
      },

      /*
        Update the cafe
      */
      updateCafe(){
        if( this.validateEditCafe() ){
          this.$store.dispatch( 'updateAdminCafe', {
            id: this.cafe.id,
            company_id: this.company.id,
            city_id: this.boundToCity,
            location_name: this.location_name,
            address: this.address,
            city: this.city,
            state: this.state,
            zip: this.zip,
            brew_methods: this.brewMethodsSelected,
            matcha: this.matcha,
            tea: this.tea,
            deleted: this.deleted
          });
        }
      },

      /*
        Validate the cafe edits.
      */
      validateEditCafe(){
        let validEditCafeForm = true;

        /*
          Ensure an address has been entered
        */
        if( this.address.trim() == '' ){
          validEditCafeForm = false;
          this.validations.address = false;
        }else{
          this.validations.address = true;
        }

        /*
          Ensure a city has been entered
        */
        if( this.city.trim() == '' ){
          validEditCafeForm = false;
          this.validations.city = false;
        }else{
          this.validations.city = true;
        }

        /*
          Ensure a state has been entered
        */
        if( this.state.trim() == '' ){
          validEditCafeForm = false;
          this.validations.state = false;
        }else{
          this.validations.state = true;
        }

        /*
          Ensure a zip has been entered
        */
        if( this.zip.trim() == '' || !this.zip.match(/(^\d{5}$)/) ){
          validEditCafeForm = false;
          this.validations.zip = false;
        }else{
          this.validations.zip = true;
        }

        return validEditCafeForm;
      }
    }
  }
</script>
