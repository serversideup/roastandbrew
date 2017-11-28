<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.tags-input-container{
    position: relative;

    div.tags-input{
      display: table;
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
      width: 100%;
      height: auto;
      min-height: 100px;
      padding-top: 4px;
      border: 1px solid #cacaca;
      border-radius: 0;
      background-color: #FFFFFF;
      -webkit-box-shadow: inset 0 1px 2px rgba(17, 17, 17, 0.1);
      box-shadow: inset 0 1px 2px rgba(17, 17, 17, 0.1);
      font-family: inherit;
      font-size: 1rem;
      font-weight: normal;
      line-height: 1.5;
      color: #111111;

      div.selected-tag{
        border: 1px solid $dark-color;
    		background: $highlight-color;
    		font-size: 18px;
    		color: $dark-color;
    		padding: 3px;
    		margin: 5px;
    		float: left;
    		border-radius: 3px;

        span.remove-tag{
      		margin: 0 0 0 5px;
      		padding: 0;
      		border: none;
      		background: none;
      		cursor: pointer;
      		vertical-align: middle;
      		color: $dark-color;
      	}
      }

      input[type="text"].new-tag-input{
        border: 0px;
        margin: 0px;
        float: left;
        width: auto;
        min-width: 100px;
        -webkit-box-shadow: none;
        box-shadow: none;
        margin: 5px;

        &.duplicate-warning{
          color: red;
        }

        &:focus{
          box-shadow: none;
        }
      }
    }

    div.tag-autocomplete{
      position: absolute;
      background-color: white;
      width: 100%;
      padding: 5px 0;
      z-index: 99999;
      border: 1px solid rgba(0,0,0,0.2);
      -webkit-box-shadow: 0 5px 10px rgba(0,0,0,0.2);
      -moz-box-shadow: 0 5px 10px rgba(0,0,0,0.2);
      box-shadow: 0 5px 10px rgba(0,0,0,0.2);

      div.tag-search-result{
        padding: 5px 10px;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: $dark-color;
        font-size: 14px;
        background-color: white;

        &:hover{
          background-color: $highlight-color;
        }
        &.selected-search-index{
          background-color: $highlight-color;
        }
      }
    }
  }
</style>

<template>
  <div class="tags-input-container">
    <label>Tags</label>
    <div class="tags-input" v-on:click="focusTagInput()">
      <div class="selected-tag" v-for="(selectedTag, key) in tagsArray">{{ selectedTag }} <span class="remove-tag" v-on:click="removeTag( key )">&times;</span> </div>
      <input type="text" v-bind:id="unique" class="new-tag-input" v-model="currentTag" v-on:keyup="searchTags" v-on:keyup.enter="addNewTag" v-on:keydown.up="changeIndex( 'up' )" v-on:keydown.delete="handleDelete" v-on:keydown.down="changeIndex( 'down' )" v-bind:class="{ 'duplicate-warning' : duplicateFlag }" placeholder="Add a tag"/>
    </div>
    <div class="tag-autocomplete" v-show="showAutocomplete">
      <div class="tag-search-result" v-for="(tag, key) in tagSearchResults" v-bind:class="{ 'selected-search-index' : searchSelectedIndex == key }" v-on:click="selectTag( tag.tag )">{{ tag.tag }}</div>
    </div>
  </div>
</template>

<script>
  /*
    Imports the Roast API URL from the config.
  */
  import { ROAST_CONFIG } from '../../../config.js';

  /*
    Imports the Event Bus to pass events on tag updates
  */
  import { EventBus } from '../../../event-bus.js';

  /*
    Imports lodash for debouncing
  */
  import _ from 'lodash';

  /*
    Exports the default components.
  */
  export default {
    props: ['unique'],

    /*
      Defines the data used by the component.
    */
    data(){
      return {
        currentTag: '',
        tagsArray: [],
        tagSearchResults: [],
        duplicateFlag: false,
        searchSelectedIndex: -1,
        pauseSearch: false
      }
    },

    /*
      Clear tags
    */
    mounted(){
      EventBus.$on('clear-tags', function( unique ){
        this.currentTag = '';
        this.tagsArray = [];
        this.tagSearchResults = [];
        this.duplicateFlag = false;
        this.searchSelectedIndex = -1;
        this.pauseSearch = false;
      }.bind(this));
    },

    /*
      Defines the computed data.
    */
    computed: {
      /*
        Determines if we should show the autocomplete or not.
      */
			showAutocomplete: function(){
				return this.tagSearchResults.length == 0 ? false : true;
			}
		},

    /*
      Defines the methods used by the component.
    */
    methods: {
      /*
        Handles the selection of a tag from the autocomplete.
      */
      selectTag( tag ){
        /*
          Check if there are duplicates in the array.
        */
        if( !this.checkDuplicates( tag ) ){
          /*
            Clean the tag name and add it to the array.
          */
          tag = this.cleanTagName( tag );
          this.tagsArray.push( tag );

          /*
            Emit the tags array and reset the inputs.
          */
          EventBus.$emit( 'tags-edited', { unique: this.unique, tags: this.tagsArray } );

          this.resetInputs();
        }else{
          /*
            Flag as duplicate
          */
          this.duplicateFlag = true;
        }
      },

      /*
        Adds a new tag from the input
      */
      addNewTag(){
        /*
          If the tag is not a duplicate, continue.
        */
        if( !this.checkDuplicates( this.currentTag ) ){
          var newTagName = this.cleanTagName( this.currentTag );
          this.tagsArray.push( newTagName );

          /*
            Emit the tags have been edited.
          */
          EventBus.$emit( 'tags-edited', { unique: this.unique, tags: this.tagsArray } );

          /*
            Reset the inputs
          */
          this.resetInputs();
        }else{
          this.duplicateFlag = true;
        }
      },

      /*
        Remove the tag from the tags array.
      */
      removeTag( tagIndex ){
        this.tagsArray.splice( tagIndex, 1 );

        /*
          Emit that the tags have been edited.
        */
        EventBus.$emit( 'tags-edited', { unique: this.unique, tags: this.tagsArray } );
      },

      /*
        Allows the user to select a tag going up or down on the
        autocomplete.
      */
      changeIndex( direction ){
        /*
          Flags to pause the search since we don't want to search on arrows up
          or down.
        */
				this.pauseSearch = true;

        /*
          If the direction is up and we are not at the beginning of the tags array,
          we move the index up and set the current tag to that in the autocomplete.
        */
				if( direction == 'up' && ( this.searchSelectedIndex -1 > -1 ) ){
					this.searchSelectedIndex = this.searchSelectedIndex - 1;
					this.currentTag = this.tagSearchResults[this.searchSelectedIndex].tag;
				}

        /*
          If the direction is down and we are not at the end of the tags array, we
          move the index down and set the current tag to that of the autocomplete.
        */
				if( direction == 'down' && ( this.searchSelectedIndex + 1 <= this.tagSearchResults.length - 1 ) ){
					this.searchSelectedIndex = this.searchSelectedIndex + 1;
					this.currentTag = this.tagSearchResults[this.searchSelectedIndex].tag;
				}
			},

      /*
        Searches the API route for tags with the autocomplete.
      */
      searchTags: _.debounce( function(e) {
        if( this.currentTag.length > 2 && !this.pauseSearch ){
          this.searchSelectedIndex = -1;
          axios.get( ROAST_CONFIG.API_URL + '/tags' , {
            params: {
              search: this.currentTag
            }
          }).then( function( response ){
            this.tagSearchResults = response.data;
          }.bind(this));
        }
      }, 300),

      /*
        Check for tag duplicates.
      */
      checkDuplicates( tagName ){
				tagName = this.cleanTagName( tagName );

				return this.tagsArray.indexOf( tagName ) > -1;
			},

      /*
        Cleans the tag to remove any unnecessary whitespace or
        symbols.
      */
      cleanTagName( tagName ){
        /*
          Convert to lower case
        */
        var cleanTag = tagName.toLowerCase();

        /*
          Trim whitespace from beginning and end of tag and
          convert anything not a letter or number to a dash.
        */
        cleanTag = cleanTag.trim().replace(/[^a-zA-Z0-9]/g,'-');

        /*
          Remove multiple instance of '-' and group to one.
        */
        cleanTag = cleanTag.replace(/-{2,}/, '-');

        /*
          Get rid of leading and trailing '-'
        */
        cleanTag = this.trimCharacter(cleanTag, '-');

        /*
          Return the clean tag
        */
        return cleanTag;
      },

      /*
        Remove the tag from the tags array.
      */
      removeTag( tagIndex ){
        this.tagsArray.splice( tagIndex, 1 );
      },

      /*
        Trims any leading or ending characters
      */
      trimCharacter (string, character) {
        if (character === "]") c = "\\]";
        if (character === "\\") c = "\\\\";
        return string.replace(new RegExp(
          "^[" + character + "]+|[" + character + "]+$", "g"
        ), "");
      },

      /*
        Reset the inputs for the tags input
      */
      resetInputs(){
        this.currentTag = '';
        this.tagSearchResults = [];
        this.duplicateFlag = false;
        this.searchSelectedIndex = -1;
				this.pauseSearch = false;
      },

      /*
        Focus on the tag input.
      */
      focusTagInput(){

				document.getElementById( this.unique ).focus();
			},

      /*
        Handles the deletion in the tag input.
      */
			handleDelete(){
				this.duplicateFlag = false;
				this.pauseSearch = false;
				this.searchSelectedIndex = -1;

        /*
          If the current tag has no data, we remove the last tag.
        */
				if( this.currentTag.length == 0 ){
					this.tagsArray.splice( this.tagsArray.length - 1, 1);
          /*
            Emit that the tags have been edited.
          */
          EventBus.$emit( 'tags-edited', { unique: this.unique, tags: this.tagsArray } );
				}
			}
    }
  }
</script>
