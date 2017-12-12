<style scoped lang="scss">
  @import '~@/abstracts/_variables.scss';

  form{
    max-width: 900px;
    margin: auto;

    h3{
      font-family: 'Josefin Sans', sans-serif;
      font-size: 24px;
      border-bottom: 1px solid $primary-color;
      color: $primary-color;
    }

    span.brew-method{
      display: block;
      width: 33%;
      float: left;
    }

    a.add-location{
      background-color: $dull-color;
      margin-top: 20px;
    }

    a.remove-location{
      color: red;
      background-color: white;
      float: right;
      text-decoration: underline;
    }

    a.save-cafe{
      float: right;
      background-color: $dark-color;
    }
  }
</style>

<template>
  <div class="page">
    <div id="cafe-edited-unsuccessfully" class="notification failure">
      Cafe Failed to be Edited Successfully! Please Try Again!
    </div>
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
            <label>Website
              <input type="text" placeholder="Website" v-model="website">
            </label>
            <span class="validation" v-show="!validations.website.is_valid">{{ validations.website.text }}</span>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <label>Photo
              <input type="file" id="cafe-photo" ref="photo" v-on:change="handleFileUpload()"/>
            </label>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <label>Description
              <textarea v-model="description"></textarea>
            </label>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <input id="is-roaster" type="checkbox" v-model="roaster" value="1"><label for="is-roaster">Is roaster?</label>
          </div>
        </div>
      </div>

      <div class="grid-container">
        <div class="grid-x grid-padding-x" v-for="(location, key) in locations">
          <div class="large-12 medium-12 small-12 cell">
            <h3>Location</h3>
          </div>
          <div class="large-6 medium-6 small-12 cell">
            <label>Location Name
              <input type="text" placeholder="Location Name" v-model="locations[key].name">
            </label>
          </div>
          <div class="large-6 medium-6 small-12 cell">
            <label>Address
              <input type="text" placeholder="Address" v-model="locations[key].address">
            </label>
            <span class="validation" v-show="!validations.locations[key].address.is_valid">{{ validations.locations[key].address.text }}</span>
          </div>
          <div class="large-6 medium-6 small-12 cell">
            <label>City
              <input type="text" placeholder="City" v-model="locations[key].city">
            </label>
            <span class="validation" v-show="!validations.locations[key].city.is_valid">{{ validations.locations[key].city.text }}</span>
          </div>
          <div class="large-6 medium-6 small-12 cell">
            <label>State
              <input type="text" placeholder="State" v-model="locations[key].state">
            </label>
            <span class="validation" v-show="!validations.locations[key].state.is_valid">{{ validations.locations[key].state.text }}</span>
          </div>
          <div class="large-6 medium-6 small-12 cell">
            <label>Zip
              <input type="text" placeholder="Zip" v-model="locations[key].zip">
            </label>
            <span class="validation" v-show="!validations.locations[key].zip.is_valid">{{ validations.locations[key].zip.text }}</span>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <label>Brew Methods Available</label>
            <span class="brew-method" v-for="brewMethod in brewMethods">
              <input v-bind:id="'brew-method-'+brewMethod.id+'-'+key" type="checkbox" v-bind:value="brewMethod.id" v-model="locations[key].methodsAvailable"><label v-bind:for="'brew-method-'+brewMethod.id+'-'+key">{{ brewMethod.method }}</label>
            </span>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <tags-input v-bind:unique="key"></tags-input>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <a class="button remove-location" v-if="key > 0" v-on:click="removeLocation( key )">Remove Location</a>
          </div>
        </div>
      </div>

      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="large-12 medium-12 small-12 cell">
            <span class="validation" v-show="!validations.oneLocation.is_valid">{{ validations.oneLocation.text }}</span>
            <a class="button add-location" v-on:click="addLocation()">+ Add Location</a>
          </div>
        </div>
      </div>

      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="large-12 medium-12 small-12 cell">
            <a class="button save-cafe" v-on:click="submitEditCafe()">Save Cafe Changes</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
  import TagsInput from '../components/global/forms/TagsInput.vue';

  /*
    Imports the Event Bus to pass events on tag updates
  */
  import { EventBus } from '../event-bus.js';

  export default {
    components: {
      TagsInput
    },

    /*
      Defines the data used by the page
    */
    data(){
      return {
        name: '',
        locations: [],
        website: '',
        description: '',
        roaster: false,
        picture: '',
        validations: {
          name: {
            is_valid: true,
            text: ''
          },
          locations: [],
          oneLocation: {
            is_valid: true,
            text: ''
          },
          website: {
            is_valid: true,
            text: ''
          }
        }
      }
    },


    /*
      Sync the tags to send to the server for the new cafe.
    */
    mounted(){
      EventBus.$on('tags-edited', function( tagsAdded ){
        this.locations[tagsAdded.unique].tags = tagsAdded.tags;
      }.bind(this));
    },

    /*
      When the component is created, add a location
    */
    created(){
      this.addLocation();

      this.$store.dispatch( 'loadCafeEdit', {
        id: this.$route.params.id
      });
    },

    /*
      Loads the Vuex data we need such as brew methods
      and edit cafe status.
    */
    computed: {
      brewMethods(){
        return this.$store.getters.getBrewMethods;
      },
      editCafeLoadStatus(){
        return this.$store.getters.getCafeEditLoadStatus;
      },
      editCafeStatus(){
        return this.$store.getters.getCafeEditStatus;
      },
      editCafe(){
        return this.$store.getters.getCafeEdit;
      }
    },

    /*
      Defines what we need to watch on the page.
    */
    watch: {
      'editCafeStatus': function(){
        if( this.editCafeStatus == 2 ){
          this.$router.push({ name: 'cafe', params: { id: this.$route.params.id }});
        }
      },
      'editCafeLoadStatus': function(){
        if( this.editCafeLoadStatus == 2 ){
          this.populateForm();
        }
      }
    },

    /*
      Defines the methods used by the page
    */
    methods: {
      /*
        Method populates the form with the data we need.
      */
      populateForm(){
        let brewMethods = [];
        let tags = [];

        /*
          Make an array of brew method IDs
        */
        for( let i = 0; i < this.editCafe.brew_methods.length; i++ ){
          brewMethods.push( this.editCafe.brew_methods[i].id );
        }

        /*
          Make an array of tags
        */
        for( let i = 0; i < this.editCafe.tags.length; i++ ){
          tags.push( this.editCafe.tags[i].tag );
        }

        /*
          Populate the form
        */
        this.name                             = this.editCafe.name;
        this.website                          = this.editCafe.website;
        this.description                      = this.editCafe.description;
        this.roaster                          = this.editCafe.roaster == 1 ? true : false;
        this.locations[0].name                = this.editCafe.location_name;
        this.locations[0].address             = this.editCafe.address;
        this.locations[0].city                = this.editCafe.city;
        this.locations[0].state               = this.editCafe.state;
        this.locations[0].zip                 = this.editCafe.zip;

        this.locations[0].methodsAvailable    = brewMethods;
        this.locations[0].tags                = tags;

        EventBus.$emit( 'set-initial-tags', { unique: 0, tags: tags } );
      },

      /*
        Submits edits for a cafe
      */
      submitEditCafe(){
        if( this.validateEditCafe() ){
          this.$store.dispatch( 'editCafe', {
            id: this.editCafe.id,
  					name: this.name,
            locations: this.locations,
            website: this.website,
            description: this.description,
            roaster: this.roaster,
            picture: this.picture
  				});
        }
      },

      /*
        Validates cafe edits
      */
      validateEditCafe(){
        let validEditCafeForm = true;

        /*
          Ensure a name has been entered
        */
        if( this.name.trim() == '' ){
          validEditCafeForm = false;
          this.validations.name.is_valid = false;
          this.validations.name.text = 'Please enter a name for the cafe!';
        }else{
          this.validations.name.is_valid = true;
          this.validations.name.text = '';
        }

        /*
          Ensure at least one location has been entered
        */
        if( this.locations.length == 0 ){
          validEditCafeForm = false;
          this.validations.oneLocation.is_valid = false;
          this.validations.oneLocation.text = 'Please enter at least one location!';
        }else{
          this.validations.oneLocation.is_valid = true;
          this.validations.oneLocation.text = '';
        }

        /*
          Ensure all locations entered are valid
        */
        for( var index in this.locations ) {
           if (this.locations.hasOwnProperty( index ) ) {
             /*
               Ensure an address has been entered
             */
             if( this.locations[index].address.trim() == '' ){
               validEditCafeForm = false;
               this.validations.locations[index].address.is_valid = false;
               this.validations.locations[index].address.text = 'Please enter an address for the cafe!';
             }else{
               this.validations.locations[index].address.is_valid = true;
               this.validations.locations[index].address.text = '';
             }
           }

           /*
             Ensure a city has been entered
           */
           if( this.locations[index].city.trim() == '' ){
             validEditCafeForm = false;
             this.validations.locations[index].city.is_valid = false;
             this.validations.locations[index].city.text = 'Please enter a city for the cafe!';
           }else{
             this.validations.locations[index].city.is_valid = true;
             this.validations.locations[index].city.text = '';
           }

           /*
             Ensure a state has been entered
           */
           if( this.locations[index].state.trim() == '' ){
             validEditEditForm = false;
             this.validations.locations[index].state.is_valid = false;
             this.validations.locations[index].state.text = 'Please enter a state for the cafe!';
           }else{
             this.validations.locations[index].state.is_valid = true;
             this.validations.locations[index].state.text = '';
           }

           /*
             Ensure a zip has been entered
           */
           if( this.locations[index].zip.trim() == '' || !this.locations[index].zip.match(/(^\d{5}$)/) ){
             validEditCafeForm = false;
             this.validations.locations[index].zip.is_valid = false;
             this.validations.locations[index].zip.text = 'Please enter a valid zip code for the cafe!';
           }else{
             this.validations.locations[index].zip.is_valid = true;
             this.validations.locations[index].zip.text = '';
           }
        }

        /*
          If a website has been entered, ensure the URL is valid
        */
        if( this.website.trim != '' && !this.website.match(/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/ ) ){
          validEditCafeForm = false;
          this.validations.website.is_valid = false;
          this.validations.website.text = 'Please enter a valid URL for the website!';
        }else{
          this.validations.website.is_valid = true;
          this.validations.website.text = '';
        }

        return validEditCafeForm;
      },

      /*
        Adds a location to the edit cafe form
      */
      addLocation(){
        this.locations.push( { name: '', address: '', city: '', state: '', zip: '', methodsAvailable: [], tags: [] } );
        this.validations.locations.push({
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
        });
      },

      /*
        Clears the form.
      */
      clearForm(){
        this.name = '';
        this.locations = [];
        this.website = '';
        this.description = '';
        this.roaster = false;
        this.picture = '';
        this.$refs.photo.value = '';

        this.validations = {
          name: {
            is_valid: true,
            text: ''
          },
          locations: [],
          oneLocation: {
            is_valid: true,
            text: ''
          },
          website: {
            is_valid: true,
            text: ''
          }
        };

        EventBus.$emit('clear-tags');

        this.addLocation();
      },

      handleFileUpload(){
        this.picture = this.$refs.photo.files[0];
      },

      /*
        Removes the location from the cafe form
      */
      removeLocation( key ){
        this.locations.splice( key, 1 );
        this.validations.locations.splice( key, 1 );
      },
    }
  }
</script>
