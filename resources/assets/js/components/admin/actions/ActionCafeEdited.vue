<style lang="scss">
  div.action-cafe-edited{
    span.change{
      color: red;
    }
  }
</style>

<template>
  <div class="action-cafe-edited action-cafe-detail">
    <div class="grid-x grid-padding-x">
      <div class="large-6 medium-6 cell">
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <span class="action-detail-header">Current</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Company</label>
            <span class="action-content">{{ action.company.name }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Website</label>
            <span class="action-content">{{ action.company.website }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Location Name</label>
            <span class="action-content">{{ action.cafe.location_name }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Address</label>
            <span class="action-content">{{ action.cafe.address }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>City</label>
            <span class="action-content">{{ action.cafe.city }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>State</label>
            <span class="action-content">{{ action.cafe.state }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Zip</label>
            <span class="action-content">{{ action.cafe.zip }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Brew Methods</label>
            <div class="brew-method option" v-for="method in action.cafe.brew_methods">
              <div class="option-container">
                <img v-bind:src="method.icon+'.svg'" class="option-icon"/> <span class="option-name">{{ method.method }}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="grid-x" v-if="action.cafe.tea == 1">
          <div class="large-12 medium-12 small-12 cell">
            <label>Tea</label>
            <div class="drink-option option">
              <div class="option-container">
                <img v-bind:src="'/img/icons/tea-bag.svg'" class="option-icon"/> <span class="option-name">Tea</span>
              </div>
            </div>
          </div>
        </div>
        <div class="grid-x" v-if="action.cafe.matcha == 1">
          <div class="large-12 medium-12 small-12 cell">
            <label>Matcha</label>
            <div class="drink-option option">
              <div class="option-container">
                <img v-bind:src="'/img/icons/matcha-latte.svg'" class="option-icon"/> <span class="option-name">Matcha</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="large-6 medium-6 cell">
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <span class="action-detail-header">Proposed Update</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Company</label>
            <span class="action-content" v-bind:class="{'change': content.after.company_name != action.company.name }">{{ content.after.company_name }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Website</label>
            <span class="action-content" v-bind:class="{'change': content.after.website != action.company.website }">{{ content.after.website }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Location Name</label>
            <span class="action-content" v-bind:class="{'change': content.after.location_name != action.cafe.location_name }">{{ content.after.location_name }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Address</label>
            <span class="action-content" v-bind:class="{'change': content.after.address != action.cafe.address }">{{ content.after.address }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>City</label>
            <span class="action-content" v-bind:class="{'change': content.after.city != action.cafe.city }">{{ content.after.city }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>State</label>
            <span class="action-content" v-bind:class="{'change': content.after.state != action.cafe.state }">{{ content.after.state }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Zip</label>
            <span class="action-content" v-bind:class="{'change': content.after.zip != action.cafe.zip }">{{ content.after.zip }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Brew Methods</label>
            <div class="brew-method option" v-for="method in actionBrewMethods">
              <div class="option-container">
                <img v-bind:src="method.icon+'.svg'" class="option-icon"/> <span class="option-name">{{ method.method }}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="grid-x" v-if="content.after.tea == 1">
          <div class="large-12 medium-12 small-12 cell">
            <label>Tea</label>
            <div class="drink-option option">
              <div class="option-container">
                <img v-bind:src="'/img/icons/tea-bag.svg'" class="option-icon"/> <span class="option-name">Tea</span>
              </div>
            </div>
          </div>
        </div>
        <div class="grid-x" v-if="content.after.matcha == 1">
          <div class="large-12 medium-12 small-12 cell">
            <label>Matcha</label>
            <div class="drink-option option">
              <div class="option-container">
                <img v-bind:src="'/img/icons/matcha-latte.svg'" class="option-icon"/> <span class="option-name">Matcha</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="large-12 medium-12 cell">
        <span class="action-information">Cafe Updated by {{ action.by.name }} on {{ action.created_at }}</span>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    /*
      Accepts an action as a property.
    */
    props: ['action'],

    /*
      Defines the data used by the component.
    */
    data(){
      return {
        content: ''
      }
    },

    /*
      When created, parses the action content to the local
      data content variable.
    */
    created(){
      this.content = JSON.parse( this.action.content );
    },

    /*
      Defines the computed properties on the component.
    */
    computed: {
      /*
        Loads the brew methods from the component.
      */
      brewMethods(){
        return this.$store.getters.getBrewMethods;
      },

      /*
        Defines the brew methods used by the action.
      */
      actionBrewMethods(){
        /*
          Initializes the action brew methods
        */
        let actionBrewMethods = [];

        /*
          Parses the brew methods array from the content.
        */
        let contentBrewMethods = JSON.parse( this.content.after.brew_methods );

        /*
          Iterate over the brew methods on the content and match with the
          brew methods defined.
        */
        for( let i = 0; i < contentBrewMethods.length; i++ ){
          for( let k = 0; k < this.brewMethods.length; k++ ){

            /*
              If the brew method IDs match, add the brew method to the action brew
              methods.
            */
            if( parseInt( contentBrewMethods[i] ) == parseInt( this.brewMethods[k].id ) ){
              actionBrewMethods.push( this.brewMethods[k] );
            }
          }
        }

        /*
          Returns the action brew methods.
        */
        return actionBrewMethods;
      }
    }
  }
</script>
