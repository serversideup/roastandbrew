<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.action{
    font-family: "Lato", sans-serif;
    border-bottom: 1px solid black;
    padding-bottom: 15px;
    padding-top: 15px;

    span.approve-action{
      font-weight: bold;
      cursor: pointer;
      display: inline-block;
      margin-right: 20px;
    }

    span.deny-action{
      color: $secondary-color;
      font-weight: bold;
      cursor: pointer;
      display: inline-block;
    }

    img.more-info{
      cursor: pointer;
      float: right;
      margin-top: 10px;
      margin-right: 10px;
    }
  }
</style>

<template>
  <div class="action">
    <div class="grid-x">
      <div class="large-3 medium-3 cell">
        {{ action.company != null ? action.company.name : '' }}
      </div>
      <div class="large-3 medium-3 cell">
        {{ action.cafe != null ? action.cafe.location_name : '' }}
      </div>
      <div class="large-3 medium-3 cell">
        {{ type }}
      </div>
      <div class="large-3 medium-3 cell">
        <span class="approve-action" v-on:click="approveAction()">Approve</span>
        <span class="deny-action" v-on:click="denyAction()">Deny</span>
        <span v-on:click="showDetails = !showDetails">
          <img src="/img/more-info-closed.svg" class="more-info" v-show="!showDetails"/>
          <img src="/img/more-info-open.svg" class="more-info" v-show="showDetails"/>
        </span>
      </div>
    </div>
    <div class="grid-x" v-show="showDetails">
      <div class="large-12 medium-12 cell">
        <action-cafe-added v-if="action.type == 'cafe-added'" :action="action"></action-cafe-added>
        <action-cafe-edited v-if="action.type == 'cafe-updated'" :action="action"></action-cafe-edited>
        <action-cafe-deleted v-if="action.type == 'cafe-deleted'" :action="action"></action-cafe-deleted>
      </div>
    </div>
  </div>
</template>

<script>
  /*
    Imports the three types of cafe actions to display.
  */
  import ActionCafeAdded from './ActionCafeAdded.vue';
  import ActionCafeEdited from './ActionCafeEdited.vue';
  import ActionCafeDeleted from './ActionCafeDeleted.vue';

  /*
    Imports the event bus to emit events.
  */
  import { EventBus } from '../../../event-bus.js';

  export default {
    /*
      Accepts an action as a property.
    */
    props: ['action'],

    /*
      Makes sure that the components used are recognized.
    */
    components: {
      ActionCafeAdded,
      ActionCafeEdited,
      ActionCafeDeleted
    },

    /*
      Defines the data used by the component.
    */
    data(){
      return {
        showDetails: false
      }
    },

    /*
      Defines the computed data used by the component.
    */
    computed: {
      /*
        Pretty prints the type of cafe.
      */
      type(){
        switch( this.action.type ){
          case 'cafe-added':
            return 'New Cafe';
          break;
          case 'cafe-updated':
            return 'Updated Cafe';
          break;
          case 'cafe-deleted':
            return 'Cafe Deleted';
          break;
        }
      },

      /*
        Gets the approved status from the Vuex store.
      */
      actionApproveStatus(){
        return this.$store.getters.getActionApproveStatus;
      },

      /*
        Gets the denied status from the Vuex store.
      */
      actionDeniedStatus(){
        return this.$store.getters.getActionDeniedStatus;
      }
    },

    /*
      Defines the watchers on the component.
    */
    watch: {
      /*
        Watches the approved status. When it's set to 2, we
        emit a success notification for the success.
      */
      'actionApprovedStatus': function(){
        if( this.actionApproveStatus == 2 ){
          EventBus.$emit('show-success', {
            notification: 'Action approved successfully!'
          });
        }
      },

      /*
        Watches the denied status. When it's set to 2, we
        emit a success notification for the denial.
      */
      'actionDeniedStatus': function(){
        if( this.actionDeniedStatus == 2 ){
          EventBus.$emit('show-success', {
            notification: 'Action denied successfully!'
          });
        }
      }
    },

    /*
      Define the methods used by the component.
    */
    methods: {
      /*
        Approves the action.
      */
      approveAction(){
        this.$store.dispatch( 'approveAction', {
          id: this.action.id
        });
      },

      /*
        Denies the action.
      */
      denyAction(){
        this.$store.dispatch( 'denyAction', {
          id: this.action.id
        });
      }
    }
  }
</script>
