<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.company{
    font-family: "Lato", sans-serif;
    border-bottom: 1px solid black;
    padding-bottom: 15px;
    padding-top: 15px;
    color: black;

    &:hover{
      background-color: #EEEEEE;
      cursor: pointer;
    }
  }

</style>

<template>
  <router-link :to="{ name: 'admin-company', params: { id: company.id } }" v-show="searchValidated">
    <div class="company">
      <div class="grid-x">
        <div class="large-3 medium-3 cell">
          {{ company.name }}
        </div>
        <div class="large-5 medium-5 cell">
          {{ company.website }}
        </div>
        <div class="large-2 medium-2 cell">
          {{ company.cafes_count }}
        </div>
        <div class="large-2 medium-2 cell">
          {{ company.actions_count }}
        </div>
      </div>
    </div>
  </router-link>
</template>

<script>
  export default {
    props: ['company', 'search'],

    /*
      Defines the computed properties on the component.
    */
    computed: {
      /*
        Determines if we show the company based on the search
        or not.
      */
      searchValidated(){
        /*
          If the search is empty, we show the company.
        */
        if( this.search == '' ){
          return true;
        }

        /*
          If the search is not empty, but the company name contains the
          search, we show the company.
        */
        if( this.search != '' && this.company.name.toLowerCase().search( this.search.toLowerCase() ) > -1 ){
          return true;
        }

        /*
          If neither scenario is valid, we hide the company.
        */
        return false;
      }
    }
  }
</script>
