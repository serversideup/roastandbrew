<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#profile-page{
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: white;
    z-index: 99999;
    overflow: auto;

    img#back{
      float: right;
      margin-top: 20px;
      margin-right: 20px;
    }

    div.centered{
      margin: auto;
    }

    h2.page-title{
      color: #342C0C;
      font-size: 36px;
      font-weight: 900;
      font-family: "Lato", sans-serif;
      margin-top: 60px;
    }

    label.form-label{
      font-family: "Lato", sans-serif;
      text-transform: uppercase;
      font-weight: bold;
      color: black;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    a.update-profile-button{
      display: block;
      text-align: center;
      height: 50px;
      color: white;
      border-radius: 3px;
      font-size: 18px;
      font-family: "Lato", sans-serif;
      background-color: #A7BE4D;
      line-height: 50px;
      margin-bottom: 50px;
    }
  }
</style>

<template>
  <transition name="scale-in-center">
    <div id="profile-page">
      <router-link :to="{ name: 'cafes' }">
        <img src="/img/close-modal.svg" id="back"/>
      </router-link>

      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="large-8 medium-9 small-12 cell centered">
            <h2 class="page-title">Profile</h2>
          </div>
        </div>
      </div>


      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <loader v-show="userLoadStatus == 1" :width="100" :height="100"></loader>
        </div>
      </div>

      <div class="grid-container" v-show="userLoadStatus == 2">
        <div class="grid-x grid-padding-x">
          <div class="large-8 medium-10 small-12 cell centered">
            <label class="form-label">Favorite Coffee</label>
            <textarea v-model="favorite_coffee"></textarea>
          </div>
        </div>
        <div class="grid-x grid-padding-x">
          <div class="large-8 medium-10 small-12 cell centered">
            <label class="form-label">Flavor Notes</label>
            <textarea v-model="flavor_notes"></textarea>
          </div>
        </div>
        <div class="grid-x grid-padding-x">
          <div class="large-8 medium-10 small-12 cell centered">
            <label class="form-label">Visibility</label>
            <select id="public-visibility" v-model="profile_visibility">
              <option value="0">Private</option>
              <option value="1">Public</option>
            </select>
          </div>
        </div>
        <div class="grid-x grid-padding-x">
          <div class="large-8 medium-10 small-12 cell centered">
            <label class="form-label">City</label>
            <input type="text" v-model="city"/>
          </div>
        </div>
        <div class="grid-x grid-padding-x">
          <div class="large-8 medium-10 small-12 cell centered">
            <label class="form-label">State</label>
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
        <div class="grid-x grid-padding-x">
          <div class="large-8 medium-10 small-12 cell centered">
            <a class="update-profile-button" v-on:click="updateProfile()">Update Profile</a>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
  /*
    Imports the event bus
  */
  import { EventBus } from '../event-bus.js';

  /*
    Imports the components used by the page.
  */
  import Loader from '../components/global/Loader.vue';

  export default {
    /*
      Defines the components used in the page.
    */
    components: {
      Loader
    },

    /*
      Defines the data used by the page.
    */
    data(){
      return {
        favorite_coffee: '',
        flavor_notes: '',
        profile_visibility: 0,
        city: '',
        state: ''
      }
    },

    /*
      Defines the data to watch in the page.
    */
    watch: {
      /*
        When the user load status changes, and is successful,
        set the fields accordingly.
      */
      'userLoadStatus': function(){
        if( this.userLoadStatus == 2 ){
          this.setFields();
        }
      },

      /*
        When the user is successfully updated, show the
        notification.
      */
      'userUpdateStatus': function(){
        if( this.userUpdateStatus == 2 ){
          EventBus.$emit('show-success', {
            notification: 'Your profile has been updated successfully!'
          });
        }
      }
    },

    /*
      On the created lifecycle hook if the user has been loaded,
      set the user fields to be edited.
    */
    created(){
      if( this.userLoadStatus == 2 ){
        this.setFields();
      }
    },

    /*
      Defines the computed fields used in the page.
    */
    computed: {
      /*
        Gets the authenticated user.
      */
      user(){
        return this.$store.getters.getUser;
      },

      /*
        Gets the user load status.
      */
      userLoadStatus(){
        return this.$store.getters.getUserLoadStatus();
      },

      /*
        Gets the user update status
      */
      userUpdateStatus(){
        return this.$store.getters.getUserUpdateStatus;
      }
    },

    /*
      Defines the methods used by the component.
    */
    methods: {
      /*
        Sets the editable fields.
      */
      setFields(){
        this.profile_visibility = this.user.profile_visibility;
        this.favorite_coffee = this.user.favorite_coffee;
        this.flavor_notes = this.user.flavor_notes;
        this.city = this.user.city;
        this.state = this.user.state;
      },

      /*
        Updates the user's profile.
      */
      updateProfile(){
        /*
          if the profile is valid, dispatch the edits to be
          stored in the API.
        */
        if( this.validateProfile() ){
          this.$store.dispatch( 'editUser', {
  					profile_visibility: this.profile_visibility,
            favorite_coffee: this.favorite_coffee,
            flavor_notes: this.flavor_notes,
            city: this.city,
            state: this.state
  				});
        }
      },

      /*
        Validates the profile
      */
      validateProfile(){
        return true;
      }
    }
  }
</script>
