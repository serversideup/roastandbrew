<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#cafe-filter{
    margin-bottom: 20px;
    border-bottom: 1px solid #ededed;
    padding-bottom: 20px;

    div.filter-brew-method{
      display: inline-block;
      height: 35px;
      text-align: center;
      border: 1px solid #ededed;
      border-radius: 5px;
      padding-left: 10px;
      padding-right: 10px;
      padding-top: 5px;
      padding-bottom: 5px;
      margin-right: 10px;
      margin-top: 10px;
      cursor: pointer;
      color: $dark-color;
      font-family: 'Josefin Sans', sans-serif;

      &.active{
        border-bottom: 4px solid $primary-color;
      }
    }

    span.show-filters{
      display: block;
      margin: auto;
      color: $dark-color;
      font-family: 'Josefin Sans', sans-serif;
      cursor: pointer;
      font-size: 14px;
    }
  }
</style>

<template>
  <div id="cafe-filter">
    <div class="grid-container" v-show="show">
      <div class="grid-x grid-padding-x">
        <div class="large-6 medium-6 small-12 cell">
          <label>Search</label>
          <input type="text" v-model="textSearch" placeholder="Search"/>

          <div class="is-roaster-container">
            <input type="checkbox" v-model="isRoaster"/> <label>Is Roaster?</label>
          </div>

          <div class="brew-methods-container">
            <div class="filter-brew-method" v-on:click="toggleBrewMethodFilter( method.method )" v-bind:class="{'active' : brewMethods.indexOf( method.method ) > -1 }" v-for="method in cafeBrewMethods">
              {{ method.method }}
            </div>
          </div>
        </div>
        <div class="large-6 medium-6 small-12 cell">
          <tags-input v-bind:unique="'cafe-search'"></tags-input>
        </div>
      </div>
    </div>
    <div class="grid-container">
      <div class="grid-x grid-padding-x">
        <span class="show-filters" v-on:click="show = !show">{{ show ? 'Hide Filters &uarr;' : 'Show Filters &darr;' }}</span>
      </div>
    </div>
  </div>
</template>

<script>
  /*
    Imports the tags input file
  */
  import TagsInput from '../global/forms/TagsInput.vue';

  /*
    Imports the Event Bus to pass updates.
  */
  import { EventBus } from '../../event-bus.js';

  export default {
    components: {
      TagsInput
    },

    data(){
      return {
        textSearch: '',
        tags: [],
        isRoaster: false,
        brewMethods: [],

        show: true
      }
    },

    /*
      Loads the Vuex data we need such as brew methods
    */
    computed: {
      cafeBrewMethods(){
        return this.$store.getters.getBrewMethods;
      },
    },

    watch: {
      textSearch(){
        this.updateFilterDisplay();
      },

      tags(){
        this.updateFilterDisplay();
      },

      isRoaster(){
        this.updateFilterDisplay();
      },

      brewMethods(){
        this.updateFilterDisplay();
      }
    },

    mounted(){
      EventBus.$on('tags-edited', function( tagsEdited ){
        if( tagsEdited.unique == 'cafe-search' ){
          this.tags = tagsEdited.tags;
        }
      }.bind(this));
    },

    methods: {
      toggleBrewMethodFilter( method ){
        if( this.brewMethods.indexOf( method ) > -1 ){
          this.brewMethods.splice( this.brewMethods.indexOf( method ), 1 );
        }else{
          this.brewMethods.push( method );
        }
      },

      updateFilterDisplay(){
        EventBus.$emit('filters-updated', {
          text: this.textSearch,
          tags: this.tags,
          roaster: this.isRoaster,
          brew_methods: this.brewMethods
        });
      }
    }
  }
</script>
