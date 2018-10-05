<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  nav.admin-navigation{
    div.admin-link{
      font-size: 16px;
      font-weight: bold;
      font-family: "Lato", sans-serif;
      text-transform: uppercase;
      padding-top: 15px;
      padding-bottom: 15px;

      a{
        color: black;

        &.router-link-active{
          color: $secondary-color;
        }
      }
    }
  }
</style>

<template>
  <nav class="admin-navigation">
    <div class="admin-link">
      <router-link :to="{ name: 'admin-actions' }">
        Actions
      </router-link>
    </div>
    <div class="admin-link">
      <router-link :to="{ name: 'admin-companies' }">
        Companies
      </router-link>
    </div>
    <div class="admin-link" v-if="user.permission >= 2">
      <router-link :to="{ name: 'admin-users' }">
        Users
      </router-link>
    </div>
    <div class="admin-link" v-if="user.permission == 3">
      <router-link :to="{ name: 'admin-brew-methods' }">
        Brew Methods
      </router-link>
    </div>
    <div class="admin-link" v-if="user.permission == 3">
      <router-link :to="{ name: 'admin-cities' }">
        Cities
      </router-link>
    </div>
  </nav>
</template>

<script>
  export default {
    computed: {
      user(){
        return this.$store.getters.getUser;
      }
    }
  }
</script>
