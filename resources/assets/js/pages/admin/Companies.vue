<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#admin-companies{
    div.companies-header{
      font-family: "Lato", sans-serif;
      border-bottom: 1px solid black;
      font-weight: bold;
      padding-bottom: 10px;

      img.sort-icon{
        display: inline-block;
        margin-left: 10px;
      }

      div.sortable-header{
        cursor: pointer;
      }
    }

    div.no-companies-available{
      text-align: center;
      font-family: "Lato", sans-serif;
      font-size: 20px;
      padding-top: 20px;
      padding-bottom: 20px;
    }
  }
</style>

<template>
  <div id="admin-companies">
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-12 medium-12 cell">
          <h3 class="page-header">Companies</h3>
        </div>
      </div>
    </div>

    <div class="grid-container">
      <div class="grid-x">
        <input type="text" v-model="search" placeholder="Search By Company Name"/>
      </div>
    </div>

    <div class="grid-container">
      <div class="grid-x companies-header">
        <div class="large-3 medium-3 cell sortable-header" v-on:click="resortCafes('name')">
          Company
          <img class="sort-icon" src="/img/sort-asc.svg" v-show="sortBy == 'name' && sortDirection == 'ASC'"/>
          <img class="sort-icon" src="/img/sort-desc.svg" v-show="sortBy == 'name' && sortDirection == 'DESC'"/>
        </div>
        <div class="large-5 medium-5 cell">
          Website
        </div>
        <div class="large-2 medium-2 cell sortable-header" v-on:click="resortCafes('cafes')">
          Cafes
          <img class="sort-icon" src="/img/sort-asc.svg" v-show="sortBy == 'cafes' && sortDirection == 'ASC'"/>
          <img class="sort-icon" src="/img/sort-desc.svg" v-show="sortBy == 'cafes' && sortDirection == 'DESC'"/>
        </div>
        <div class="large-2 medium-2 cell sortable-header" v-on:click="resortCafes('pending-actions')">
          Actions Pending
          <img class="sort-icon" src="/img/sort-asc.svg" v-show="sortBy == 'pending-actions' && sortDirection == 'ASC'"/>
          <img class="sort-icon" src="/img/sort-desc.svg" v-show="sortBy == 'pending-actions' && sortDirection == 'DESC'"/>
        </div>
      </div>

      <company v-for="company in companies"
        :key="company.id"
        :company="company"
        :search="search">
      </company>

      <div class="large-12 medium-12 cell no-companies-available" v-show="companies.length == 0">
        No companies available
      </div>
    </div>
  </div>
</template>

<script>
  /*
    Imports the components used by the page.
  */
  import Company from '../../components/admin/companies/Company.vue';

  export default {
    /*
      Defines the data used by the page.
    */
    data(){
      return {
        sortBy: 'name',
        sortDirection: 'ASC',

        search: ''
      }
    },

    /*
      Registers the components with the page.
    */
    components: {
      Company
    },

    /*
      Sets up the page
    */
    created(){
      this.$store.dispatch( 'loadAdminCompanies' );
    },

    /*
      Defines the computed properties on the page.
    */
    computed: {
      /*
        Gets the companies from the Vuex data store.
      */
      companies(){
        return this.$store.getters.getCompanies;
      },

      /*
        Gets the companies from the Vuex data store.
      */
      companiesLoadStatus(){
        return this.$store.getters.getCompaniesLoadStatus;
      }
    },

    /*
      Defines the watchers on the page.
    */
    watch: {
      'companiesLoadStatus': function(){
        this.resortCafes( 'name' );
      }
    },

    /*
      Defines the methods used by the page.
    */
    methods: {
      /*
        Re-sorts the cafes by what was selected.
      */
      resortCafes( by ){
        /*
          Checks to see if what the user selected to sort by
          is the same as it's been. If it is, then we toggle the
          direction.
        */
        if( by == this.sortBy ){
          if( this.sortDirection == 'ASC' ){
            this.sortDirection = 'DESC';
          }else{
            this.sortDirection = 'ASC';
          }
        }

        /*
          If the sort by is different we set the sort by to what the
          user selected and defualt the direction to 'ASC'
        */
        if( by != this.sortBy ){
          this.sortDirection = 'ASC';
          this.sortBy = by;
        }

        /*
          Switch by what the sort by is set to, and run the method
          to sort by that column.
        */
        switch( this.sortBy ){
          case 'name':
            this.sortCompaniesByName();
          break;
          case 'cafes':
            this.sortCompaniesByCafes();
          break;
          case 'pending-actions':
            this.sortCompaniesByPendingActions();
          break;
        }
      },

      /*
        Sorts the companies by name.
      */
      sortCompaniesByName(){
        this.companies.sort( function( a, b ){
          if( this.sortDirection == 'ASC' ){
            return ( ( a.name == b.name ) ? 0 : ( ( a.name > b.name ) ? 1 : -1 ) );
          }

          if( this.sortDirection == 'DESC' ){
            return ( ( a.name == b.name ) ? 0 : ( ( a.name < b.name ) ? 1 : -1 ) );
          }
        }.bind(this));
      },

      /*
        Sorts the companies by cafe count.
      */
      sortCompaniesByCafes(){
        this.companies.sort( function( a, b ){
          if( this.sortDirection == 'ASC' ){
            return parseInt( a.cafes_count ) < parseInt( b.cafes_count ) ? 1 : -1;
          }

          if( this.sortDirection == 'DESC' ){
            return parseInt( a.cafes_count ) > parseInt( b.cafes_count ) ? 1 : -1;
          }
        }.bind(this));
      },

      /*
        Sorts the companies by pending actions.
      */
      sortCompaniesByPendingActions(){
        this.companies.sort( function( a, b ){
          if( this.sortDirection == 'ASC' ){
            return parseInt( a.actions_count ) < parseInt( b.actions_count ) ? 1 : -1;
          }

          if( this.sortDirection == 'DESC' ){
            return parseInt( a.actions_count ) > parseInt( b.actions_count ) ? 1 : -1;
          }
        }.bind(this));
      }
    }
  }
</script>
