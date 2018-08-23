<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#admin-company{
    label{
      font-weight: bold;
      color: black;
      font-size: 16px;
      margin-top: 15px;
    }

    div.cafes-header{
      font-family: "Lato", sans-serif;
      border-bottom: 1px solid black;
      font-weight: bold;
      padding-bottom: 10px;
    }

    div.user-selection-container{
      position: relative;
      margin-top: 20px;

      div.user-autocomplete-container{
        border-radius: 3px;
        border: 1px solid #BABABA;
        background-color: white;
        margin-top: -17px;
        width: 80%;
        position: absolute;
        z-index: 9999;

        div.user-autocomplete{
          cursor: pointer;
          padding-left: 12px;
          padding-right: 12px;
          padding-top: 8px;
          padding-bottom: 8px;

          span.user-name{
            display: block;
            color: #0D223F;
            font-size: 16px;
            font-family: "Lato", sans-serif;
            font-weight: bold;
          }

          &:hover{
            background-color: #F2F2F2;
          }
        }
      }
    }


    div.location-type{
      display: inline-block;
      margin-right: 10px;
      cursor: pointer;
      background-color: #CCC;

      &.active{
        color: white;
        background-color: $secondary-color;
      }
    }

    div.owner{
      padding-top: 10px;
      padding-bottom: 10px;
      border-bottom: 1px solid black;

      a.remove-owner{
        text-decoration: underline;
        color: red;
        float: right;
      }
    }

    a.save-edits{
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
</style>

<template>
  <div id="admin-company">
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-12 medium-12 cell">
          <h3 class="page-header"><router-link :to="{ name: 'admin-companies'}">Companies</router-link> > {{ company.name }}</h3>
        </div>
      </div>
    </div>

    <div class="grid-container">
      <div class="grid-x admin-tabs">
        <div class="tab" v-on:click="tab = 'information'" v-bind:class="{'active-tab': tab == 'information'}">
          Information
        </div>
        <div class="tab" v-on:click="tab = 'cafes'" v-bind:class="{'active-tab': tab == 'cafes'}">
          Cafes
        </div>
        <div class="tab" v-on:click="tab = 'history'" v-bind:class="{'active-tab': tab == 'history'}">
          History
        </div>
      </div>
    </div>

    <div class="grid-container" v-show="tab == 'information'">
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Company Name</label>
          <input type="text" v-model="name"/>
          <span class="validation" v-show="!validations.name">Please enter a name for the company</span>
        </div>
      </div>
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Type</label>
          <div class="location-type roaster" v-bind:class="{ 'active': type == 'roaster' }" v-on:click="setCompanyType('roaster')">
            Roaster
          </div><div class="location-type cafe" v-bind:class="{ 'active': type == 'cafe' }" v-on:click="setCompanyType('cafe')">
            Cafe
          </div>
        </div>
      </div>
      <div class="grid-x grid-padding-x" v-show="type == 'roaster'">
        <div class="large-8 medium-9 small-12 cell centered">
          <label>Does the roaster offer a subscription service?</label>
        </div>
      </div>

      <div class="grid-x grid-padding-x" v-show="type == 'roaster'">
        <div class="large-8 medium-9 small-12 cell centered">
          <div class="subscription-option option" v-on:click="subscription == 0 ? subscription = 1 : subscription = 0" v-bind:class="{'active': subscription == 1}">
            <div class="option-container">
              <img src="/img/icons/coffee-pack.svg" class="option-icon"/> <span class="option-name">Coffee Subscription</span>
            </div>
          </div>
        </div>
      </div>
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Website</label>
          <input type="text" v-model="website"/>
          <span class="validation" v-show="!validations.website">Please enter a valid website</span>
        </div>
      </div>
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Owners</label>
          <div class="no-owners" v-show="owners.length == 0">N/A</div>
          <div class="owner" v-for="(owner, key) in owners">
            <router-link v-if="user.permission > 1" :to="{name: 'admin-user', params: { 'id': owner.id } }">{{ owner.name }}</router-link>
            <span v-if="user.permission == 1">{{ owner.name }}</span>

            <a class="remove-owner" v-if="user.permission > 1" v-on:click="removeOwner( key )">Remove</a>
          </div>

          <div class="user-selection-container" v-if="user.permission > 1">
            <input type="text" class="new-owner" v-model="newOwner" v-on:keyup="searchUsers()" placeholder="Add An Owner"/>

            <div class="user-autocomplete-container" v-show="newOwner.length > 0 && showAutocomplete">
              <div class="user-autocomplete" v-for="user in newOwnerResults" v-on:click="selectUser( user )">
                <span class="user-name">{{ user.name }}</span>
              </div>
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
          <a class="save-edits" v-on:click="saveEdits()">Update Company</a>
        </div>
      </div>
    </div>
    <div class="grid-container" v-show="tab == 'cafes'">
      <div class="grid-x cafes-header">
        <div class="large-3 medium-3 cell">
          Location Name
        </div>
        <div class="large-6 medium-6 cell">
          Address
        </div>
        <div class="large-3 medium-3 cell">

        </div>
      </div>
      <cafe v-for="cafe in company.cafes"
        :cafe="cafe"
        :key="cafe.id"
        ></cafe>
    </div>
  </div>
</template>

<script>
  /*
    Imports components used by the page.
  */
  import Cafe from '../../components/admin/companies/Cafe.vue';

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
    /*
      Defines the data used by the page.
    */
    data(){
      return {
        tab: 'information',

        newOwner: '',
        newOwnerResults: [],
        showAutocomplete: true,

        name: '',
        type: '',
        subscription: 0,
        website: '',
        owners: [],
        deleted: 0,

        validations: {
          name: true,
          type: true,
          website: true,
          owners: true
        }
      }
    },

    /*
      Registers the components with the page.
    */
    components: {
      Cafe
    },

    /*
      Sets up the page.
    */
    created(){
      this.$store.dispatch( 'loadAdminCompany', { id: this.$route.params.id } );
    },

    /*
      Defines the computed properties used by the page.
    */
    computed: {
      /*
        Gets the authenticated user from the Vuex data store.
      */
      user(){
        return this.$store.getters.getUser;
      },

      /*
        Returns the company from the Vuex data store.
      */
      company(){
        return this.$store.getters.getCompany;
      },

      /*
        Returns the company load status from the data store.
      */
      companyLoadStatus(){
        return this.$store.getters.getCompanyLoadStatus;
      },

      /*
        Returns the company edit status from the data store.
      */
      companyEditStatus(){
        return this.$store.getters.getCompanyEditStatus;
      }
    },

    /*
      Defines the watchers used by the page.
    */
    watch: {
      /*
        When the company is loaded successfully, we sync the
        data to the local model for editing.
      */
      'companyLoadStatus': function(){
        if( this.companyLoadStatus == 2 ){
          this.syncCompanyToModel();
        }
      },

      /*
        When the company has been edited successfully, we sync
        the data to the local model for editing and display a
        notificaiton.
      */
      'companyEditStatus': function(){
        if( this.companyEditStatus == 2 ){
          this.syncCompanyToModel();

          EventBus.$emit('show-success', {
            notification: 'Company updated successfully!'
          });
        }
      }
    },

    /*
      Defines the methods used by the page.
    */
    methods: {
      /*
        Toggles the company type.
      */
      setCompanyType( type ){
        this.type = type;
      },

      /*
        Removes an owner
      */
      removeOwner( index ){
        this.owners.splice( index, 1 );
      },

      /*
        Saves all edits.
      */
      saveEdits(){
        if( this.validateEditCompany() ){
          this.$store.dispatch( 'updateAdminCompany', {
            id: this.company.id,
            name: this.name,
            type: this.type,
            website: this.website,
            subscription: this.subscription,
            owners: this.owners,
            deleted: this.deleted
          });
        }
      },

      /*
        Validates all edits.
      */
      validateEditCompany(){
        let validCompanyForm = true;

        /*
          Ensure a name has been entered
        */
        if( this.name.trim() == '' ){
          validCompanyForm = false;
          this.validations.name = false;
        }else{
          this.validations.name = true;
        }

        /*
          If a website has been entered, ensure the URL is valid
        */
        if( this.website.trim != '' && !this.website.match(/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/ ) ){
          validNewCafeForm = false;
          this.validations.website = false;
        }else{
          this.validations.website = true;
        }

        return validCompanyForm;
      },

      /*
        Syncs the company data to the model data.
      */
      syncCompanyToModel(){
        this.name = this.company.name;
        this.type = this.company.roaster == 1 ? 'roaster' : 'cafe';
        this.subscription = this.company.subscription;
        this.website = this.company.website;
        this.owners = this.company.owned_by;
        this.deleted = this.company.deleted;
      },

      /*
        Searches the API route for users
      */
      searchUsers: _.debounce( function(e) {
        /*
          Ensures something is entered before searching companies.
        */
        if( this.newOwner.length > 1){
          this.showAutocomplete = true;

          /*
            Search for the users.
          */
          axios.get( ROAST_CONFIG.API_URL + '/admin/users' , {
            params: {
              search: this.newOwner
            }
          }).then( function( response ){
            this.newOwnerResults = response.data;
          }.bind(this));
        }
      }, 300),

      /*
        Selects a new owner
      */
      selectUser( user ){
        this.owners.push( user );

        this.newOwner = '';
        this.showAutocomplete = false;
      }
    }
  }
</script>
