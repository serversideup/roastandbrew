<style lang="scss">
  @import '~@/abstracts/_variables.scss';
</style>

<template>
  <div class="action-cafe-added action-cafe-detail">
    <div class="grid-x grid-padding-x">
      <div class="large-6 medium-6 cell">
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Company</label>
            <span class="action-content">{{ content.company_name }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Website</label>
            <span class="action-content">{{ content.website }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Location Name</label>
            <span class="action-content">{{ content.location_name }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Address</label>
            <span class="action-content">{{ content.address }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>City</label>
            <span class="action-content">{{ content.city }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>State</label>
            <span class="action-content">{{ content.state }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Zip</label>
            <span class="action-content">{{ content.zip }}</span>
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
        <div class="grid-x" v-if="content.tea == 1">
          <div class="large-12 medium-12 small-12 cell">
            <label>Tea</label>
            <div class="drink-option option">
              <div class="option-container">
                <img v-bind:src="'/img/icons/tea-bag.svg'" class="option-icon"/> <span class="option-name">Tea</span>
              </div>
            </div>
          </div>
        </div>
        <div class="grid-x" v-if="content.matcha == 1">
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
        <span class="action-information">Cafe Added by {{ action.by.name }} on {{ action.created_at }}</span>
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
        let contentBrewMethods = JSON.parse( this.content.brew_methods );

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
