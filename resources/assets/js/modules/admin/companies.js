/*
|-------------------------------------------------------------------------------
| VUEX modules/admin/companies.js
|-------------------------------------------------------------------------------
| The Vuex data store for the admin companies
*/
import CompaniesAPI from '../../api/admin/companies.js';

export const companies = {
  /*
    Defines the state monitored for the module.
  */
  state: {
    companies: [],
    companiesLoadStatus: 0,

    company: {},
    companyLoadStatus: 0,

    companyEditStatus: 0
  },

  /*
    Define the actions that can mutate the state.
  */
  actions: {
    /*
      Loads the admin companies.
    */
    loadAdminCompanies( { commit } ){
      commit( 'setCompaniesLoadStatus', 1 );

      /*
        Calls the API to load the admin companies.
      */
      CompaniesAPI.getCompanies()
        .then( function( response ){
          /*
            Commit a successful response with the companies.
          */
          commit( 'setCompanies', response.data );
          commit( 'setCompaniesLoadStatus', 2 );
        })
        .catch( function(){
          /*
            Commit a failed response and clear the data.
          */
          commit( 'setCompanies', [] );
          commit( 'setCompaniesLoadStatus', 3 );
        });
    },

    /*
      Loads an admin company
    */
    loadAdminCompany( { commit }, data ){
      commit( 'setCompanyLoadStatus', 1 );

      /*
        Calls the API to load an admin company.
      */
      CompaniesAPI.getCompany( data.id )
        .then( function( response ){
          /*
            Commit a successful response with the company.
          */
          commit( 'setCompany', response.data );
          commit( 'setCompanyLoadStatus', 2 );
        })
        .catch( function(){
          /*
            Commit a failed response and clear the data.
          */
          commit( 'setCompany', {} );
          commit( 'setCompanyLoadStatus', 3 );
        });
    },

    /*
      Updates an admin company
    */
    updateAdminCompany( { commit }, data ){
      commit( 'setCompanyEditStatus', 1 );

      /*
        Calls the API to update an admin company.
      */
      CompaniesAPI.putUpdateCompany( data.id, data.name, data.type, data.website, data.owners, data.deleted )
        .then( function( response ){
          commit( 'setCompany', response.data );
          commit( 'setCompanyEditStatus', 2 );
        })
        .catch( function(){
          /*
            Set a failed response.
          */
          commit( 'setCompanyEditStatus', 3 );
        });
    }
  },

  /*
    Defines the mutations used by the Vuex module.
  */
  mutations: {
    /*
      Sets the companies load status.
    */
    setCompaniesLoadStatus( state, status ){
      state.companiesLoadStatus = status;
    },

    /*
      Sets the companies
    */
    setCompanies( state, companies ){
      state.companies = companies;
    },

    /*
      Sets the company load status.
    */
    setCompanyLoadStatus( state, status ){
      state.companyLoadStatus = status;
    },

    /*
      Sets the company
    */
    setCompany( state, company ){
      state.company = company;
    },

    /*
      Sets the company edited status
    */
    setCompanyEditStatus( state, status ){
      state.companyEditStatus = status;
    }
  },

  /*
    Defines the getters used by the Vuex module.
  */
  getters: {
    /*
      Returns the companies
    */
    getCompanies( state ){
      return state.companies;
    },

    /*
      Get Companies load status.
    */
    getCompaniesLoadStatus( state ){
      return state.companiesLoadStatus;
    },

    /*
      Get company
    */
    getCompany( state ){
      return state.company;
    },

    /*
      Get company load status.
    */
    getCompanyLoadStatus( state ){
      return state.companyLoadStatus;
    },

    /*
      Gets the company edited status.
    */
    getCompanyEditStatus( state ){
      return state.companyEditStatus;
    }
  }
}
