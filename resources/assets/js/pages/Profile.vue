<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#profile{
    div.center{
      margin: auto;
    }
  }
</style>

<template>
  <div id="profile" class="page">
    <div id="profile-updated-successfully" class="notification success">
      Profile Updated Successfully!
    </div>

    <div class="grid-container">
      <div class="grid-x grid-padding-x">
        <loader v-show="userLoadStatus == 1" :width="100" :height="100"></loader>
      </div>
    </div>

    <div class="grid-container" v-show="userLoadStatus == 2">
      <div class="grid-x grid-padding-x">
        <div class="large-8 medium-10 small-12 cell center">
          <label>Favorite Coffee
            <textarea v-model="favorite_coffee"></textarea>
          </label>
        </div>
      </div>
      <div class="grid-x grid-padding-x">
        <div class="large-8 medium-10 small-12 cell center">
          <label>Flavor Notes
            <textarea v-model="flavor_notes"></textarea>
          </label>
        </div>
      </div>
      <div class="grid-x grid-padding-x">
        <div class="large-8 medium-10 small-12 cell center">
          <label>Visibility
            <select id="public-visibility" v-model="profile_visibility">
              <option value="0">Private</option>
              <option value="1">Public</option>
            </select>
          </label>
        </div>
      </div>
      <div class="grid-x grid-padding-x">
        <div class="large-8 medium-10 small-12 cell center">
          <label>City
            <input type="text" v-model="city"/>
          </label>
        </div>
      </div>
      <div class="grid-x grid-padding-x">
        <div class="large-8 medium-10 small-12 cell center">
          <label>State
            <input type="text" v-model="state"/>
          </label>
        </div>
      </div>
      <div class="grid-x grid-padding-x">
        <div class="large-8 medium-10 small-12 cell center">
          <a class="button update-profile" v-on:click="updateProfile()">Update Profile</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Loader from '../components/global/Loader.vue';

  export default {
    components: {
      Loader
    },

    data(){
      return {
        favorite_coffee: '',
        flavor_notes: '',
        profile_visibility: 0,
        city: '',
        state: ''
      }
    },

    watch: {
      'userLoadStatus': function(){
        if( this.userLoadStatus == 2 ){
          this.setFields();
        }
      },

      'userUpdateStatus': function(){
        if( this.userUpdateStatus == 2 ){
          $("#profile-updated-successfully").show().delay(5000).fadeOut();
        }
      }
    },

    created(){
      if( this.userLoadStatus == 2 ){
        this.setFields();
      }
    },

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
      setFields(){
        this.profile_visibility = this.user.profile_visibility;
        this.favorite_coffee = this.user.favorite_coffee;
        this.flavor_notes = this.user.flavor_notes;
        this.city = this.user.city;
        this.state = this.user.state;
      },

      updateProfile(){
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

      validateProfile(){
        return true;
      }
    }
  }
</script>
