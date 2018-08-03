/*
|-------------------------------------------------------------------------------
| VUEX modules/admin/actions.js
|-------------------------------------------------------------------------------
| The Vuex data store for the admin actions
*/
import ActionsAPI from '../../api/admin/actions.js';

export const actions = {
  /*
    Defines the state being monitored for the module.
  */
  state: {
    actions: [],
    actionsLoadStatus: 0,

    actionApproveStatus: 0,
    actionDeniedStatus: 0
  },

  /*
    Define the actions that mutate the state.
  */
  actions: {
    /*
      Loads the admin actions.
    */
    loadAdminActions( { commit } ){
      commit( 'setActionsLoadStatus', 1 );

      /*
        Calls the API to load the admin actions.
      */
      ActionsAPI.getActions()
        .then( function( response ){
          /*
            Commit a successful response with the actions.
          */
          commit( 'setActions', response.data );
          commit( 'setActionsLoadStatus', 2 );
        })
        .catch( function(){
          /*
            Commit a failed response and clear the data.
          */
          commit( 'setActions', [] );
          commit( 'setActionsLoadStatus', 3 );
        });
    },

    /*
      Approves an action.
    */
    approveAction( { commit, state, dispatch }, data ){
      commit( 'setActionApproveStatus', 1 );

      /*
        Calls the API to approve an action.
      */
      ActionsAPI.putApproveAction( data.id )
        .then( function( response ){
          commit( 'setActionApproveStatus', 2 );
          dispatch( 'loadAdminActions' );
        })
        .catch( function(){
          commit( 'setActionApproveStatus', 3 );
        });

    },

    /*
      Denies an action.
    */
    denyAction( { commit, state, dispatch }, data ){
      commit( 'setActionDeniedStatus', 1 );

      /*
        Calls the API to deny an action.
      */
      ActionsAPI.putDenyAction( data.id )
        .then( function( response ){
          commit( 'setActionDeniedStatus', 2 );
          dispatch( 'loadAdminActions' );
        })
        .catch( function(){
          commit( 'setActionDeniedStatus', 3 );
        });

    }
  },

  /*
    Defines the mutations used by the Vuex modeule
  */
  mutations: {
    /*
      Sets the action load status.
    */
    setActionsLoadStatus( state, status ){
      state.actionsLoadStatus = status;
    },

    /*
      Sets the actions.
    */
    setActions( state, actions ){
      state.actions = actions;
    },

    /*
      Sets the action approve status
    */
    setActionApproveStatus( state, status ){
      state.actionApproveStatus = status;
    },

    /*
      Sets the action denied status
    */
    setActionDeniedStatus( state, status ){
      state.actionDeniedStatus = status;
    }
  },

  /*
    Defines the getters on the Vuex module.
  */
  getters: {
    /*
      Returns the actions
    */
    getActions( state ){
      return state.actions;
    },

    /*
      Returns the actions load status
    */
    getActionsLoadStatus( state ){
      return state.actionsLoadStatus;
    },

    /*
      Returns the actions approve status
    */
    getActionApproveStatus( state ){
      return state.actionApproveStatus;
    },

    /*
      Returns the actions denied status
    */
    getActionDeniedStatus( state ){
      return state.actionDeniedStatus;
    }
  }
}
