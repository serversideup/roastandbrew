<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.cafe-listing{
    padding-top: 10px;
    padding-bottom: 10px;
  }
</style>

<template>
  <div class="grid-x cafe-listing">
    <div class="large-3 medium-3 cell">
      {{ cafe.location_name }}
    </div>
    <div class="large-6 medium-6 cell">
      {{ cafe.address }} {{ cafe.city }}, {{ cafe.state }}
    </div>
    <div class="large-3 medium-3 cell">
      <router-link :to="{'name': 'admin-cafe', params: { id: cafe.company_id, cafeID: cafe.id } }">
        More Info
      </router-link>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['cafe'],

  }
</script>
