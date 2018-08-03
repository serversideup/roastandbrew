<style>

</style>

<template>
  <div class="action-cafe-deleted action-cafe-detail">
    <div class="grid-x grid-padding-x">
      <div class="large-6 medium-6 cell">
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Company</label>
            <span class="action-content">{{ action.cafe.company_name }}</span>
          </div>
        </div>
        <div class="grid-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Website</label>
            <span class="action-content">{{ action.cafe.website }}</span>
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
      <div class="large-12 medium-12 cell">
        <span class="action-information">Cafe Deleted by {{ action.by.name }} on {{ action.created_at }}</span>
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
    }
  }
</script>
