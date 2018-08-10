<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#admin-users{
    label{
      font-weight: bold;
      color: black;
      font-size: 16px;
      margin-top: 15px;
    }

    img.large-avatar{
      display: block;
      width: 50px;
      height: 50px;
      border-radius: 50px;
    }

    a.update-user{
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

    div.company{
      padding-top: 10px;
      padding-bottom: 10px;
      border-bottom: 1px solid black;

      a.remove-company{
        text-decoration: underline;
        color: red;
        float: right;
      }
    }

    input[type="text"].company-owner-input{
      margin-top: 30px;
    }

    div.company-selection-container{
      position: relative;

      div.company-autocomplete-container{
        border-radius: 3px;
        border: 1px solid #BABABA;
        background-color: white;
        margin-top: -17px;
        width: 80%;
        position: absolute;
        z-index: 9999;

        div.company-autocomplete{
          cursor: pointer;
          padding-left: 12px;
          padding-right: 12px;
          padding-top: 8px;
          padding-bottom: 8px;

          span.company-name{
            display: block;
            color: #0D223F;
            font-size: 16px;
            font-family: "Lato", sans-serif;
            font-weight: bold;
          }

          span.company-locations{
            display: block;
            font-size: 14px;
            color: #676767;
            font-family: "Lato", sans-serif;
          }

          &:hover{
            background-color: #F2F2F2;
          }
        }

        div.new-company{
          cursor: pointer;
          padding-left: 12px;
          padding-right: 12px;
          padding-top: 8px;
          padding-bottom: 8px;
          font-family: "Lato", sans-serif;
          color: #054E7A;
          font-style: italic;

          &:hover{
            background-color: #F2F2F2;
          }
        }
      }
    }
  }
</style>

<template>
  <div id="admin-users">
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-12 medium-12 cell">
          <h3 class="page-header"><router-link :to="{ name: 'admin-users'}">Users</router-link> > {{ adminUser.name }}</h3>
        </div>
      </div>

      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Avatar</label>
          <img class="large-avatar" v-bind:src="adminUser.avatar"/>
        </div>
      </div>

      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Name</label>
          {{ adminUser.name }}
        </div>
      </div>

      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Email</label>
          {{ adminUser.email }}
        </div>
      </div>

      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Permission Level</label>
          <select v-model="permission">
            <option value="0">General User</option>
            <option value="1">Owner</option>
            <option value="2">Admin</option>
            <option value="3" v-if="user.permission == 3">Super Admin</option>
          </select>
        </div>
      </div>

      <div class="grid-x">
        <div class="large-8 medium-12 cell company-selection-container">
          <label>Companies Owned</label>

          <span class="no-companies-owned" v-if="companies.length == 0">N/A</span>
          
          <div class="company" v-for="(company, key) in companies">
            <router-link :to="{ name: 'admin-company', params: { id: company.id } }">{{ company.name }}</router-link>
            <a class="remove-company" v-on:click="removeCompany( key )">Remove</a>
          </div>

          <input type="text" class="form-input company-owner-input" v-model="companyName" v-on:keyup="searchCompanies()"/>
          <div class="company-autocomplete-container" v-show="companyName.length > 0 && showAutocomplete">
            <div class="company-autocomplete" v-for="companyResult in companyResults" v-on:click="selectCompany( companyResult )">
              <span class="company-name">{{ companyResult.name }}</span>
              <span class="company-locations">{{ companyResult.cafes_count }} location<span v-if="companyResult.cafes_count > 1">s</span></span>
            </div>
          </div>
        </div>
      </div>

      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <a class="update-user" v-on:click="updateProfile()">Update Profile</a>
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

  /*
    Imports lodash for debouncing
  */
  import _ from 'lodash';

  /*
    Imports the config for the API URL for searching companies.
  */
  import { ROAST_CONFIG } from '../../config.js';

  export default {
    data(){
      return {
        companyName: '',
        companyResults: [],
        showAutocomplete: false,

        permission: 0,
        companies: []
      }
    },

    /*
      Sets up the page.
    */
    created(){
      this.$store.dispatch( 'loadAdminUser', { id: this.$route.params.id } );
    },

    /*
      Defines the computed properties used by the page.
    */
    computed: {
      /*
        Gets the authenticated user from the data store.
      */
      user(){
        return this.$store.getters.getUser;
      },

      /*
        Gets the admin user from the data store.
      */
      adminUser(){
        return this.$store.getters.getAdminUser;
      },

      /*
        Gets the admin user load status from the data store.
      */
      adminUserLoadStatus(){
        return this.$store.getters.getAdminUserLoadStatus;
      },

      /*
        Gets the admin user update status from the data store.
      */
      adminUserUpdateStatus(){
        return this.$store.getters.getAdminUserUpdateStatus;
      }
    },

    /*
      Defines what should be watched by the page
    */
    watch: {
      /*
        Watches the admin user load status and copies the
        user to the model.
      */
      'adminUserLoadStatus': function(){
        if( this.adminUserLoadStatus == 2 ){
          this.syncUserToModel();
        }
      },

      /*
        Watches the admin user update status and copies the
        user to the model.
      */
      'adminUserUpdateStatus': function(){
        if( this.adminUserUpdateStatus == 2 ){
          this.syncUserToModel();

          EventBus.$emit('show-success', {
            notification: 'User updated successfully!'
          });
        }
      }
    },

    /*
      Defines the methods used by the page.
    */
    methods: {
      /*
        Syncs the user to the model.
      */
      syncUserToModel(){
        this.permission = this.adminUser.permission;
        this.companies = this.adminUser.companies_owned
      },

      /*
        Updates the profile.
      */
      updateProfile(){
        this.$store.dispatch( 'updateAdminUser', {
          id: this.adminUser.id,
          permission: this.permission,
          companies: this.companies
        });
      },

      /*
        Searches the API route for companies
      */
      searchCompanies: _.debounce( function(e) {
        /*
          Ensures something is entered before searching companies.
        */
        if( this.companyName.length > 1){
          this.showAutocomplete = true;
          axios.get( ROAST_CONFIG.API_URL + '/admin/companies' , {
            params: {
              search: this.companyName
            }
          }).then( function( response ){
            this.companyResults = response.data;
          }.bind(this));
        }
      }, 300),

      /*
        Selects an existing company
      */
      selectCompany( company ){
        this.showAutocomplete = false;
        this.companies.push( company );
        this.companyResults = [];
        this.companyName = '';
      },

      /*
        Removes a company
      */
      removeCompany( index ){
        this.companies.splice( index, 1 );
      }
    }
  }
</script>
