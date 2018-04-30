<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  span.toggle-like{

    span.like-toggle{
      display: inline-block;
      cursor: pointer;
      color: #8E8E8E;
      font-size: 18px;
      margin-bottom: 5px;

      span.image-container{
        width: 35px;
        text-align: center;
        display: inline-block;
      }
    }

    span.like-count{
      font-family: "Lato", sans-serif;
      font-size: 12px;
      margin-left: 10px;
      color: #8E8E8E;
    }
  }
</style>
<template>
  <span class="toggle-like" v-show="userLoadStatus == 2 && user != ''">
    <span class="like like-toggle" v-on:click="likeCafe( cafe.id )" v-if="!liked && cafeLoadStatus == 2 && cafeLikeActionStatus != 1 && cafeUnlikeActionStatus != 1">
      <span class="image-container">
        <img src="/img/unliked.svg"/>
      </span> Like?
    </span>
    <span class="un-like like-toggle" v-on:click="unlikeCafe( cafe.id )" v-if="liked && cafeLoadStatus == 2 && cafeLikeActionStatus != 1 && cafeUnlikeActionStatus != 1">
      <span class="image-container">
        <img src="/img/liked.svg"/>
      </span> Liked
    </span>
    <loader v-show="cafeLikeActionStatus == 1 || cafeUnlikeActionStatus == 1 || cafeLoadStatus != 2"
            :width="23"
            :height="23"
            :display="'inline-block'">
    </loader>
    <span class="like-count">
      {{ cafe.likes_count }} likes
    </span>
  </span>
</template>
<script>
  import Loader from '../global/Loader.vue';

  export default {
    components: {
      Loader
    },

    computed: {
      /*
        Retrieves the User Load Status from Vuex
      */
      userLoadStatus(){
        return this.$store.getters.getUserLoadStatus();
      },

      /*
        Retrieves the User from Vuex
      */
      user(){
        return this.$store.getters.getUser;
      },

      /*
        Gets the cafe load status from the Vuex state.
      */
      cafeLoadStatus(){
        return this.$store.getters.getCafeLoadStatus;
      },

      /*
        Gets the cafe from the Vuex state.
      */
      cafe(){
        return this.$store.getters.getCafe;
      },

      /*
        Determines if the cafe is liked or not.
      */
      liked(){
        return this.$store.getters.getCafeLikedStatus;
      },

      /*
        Determines if the cafe is still processing the like action.
      */
      cafeLikeActionStatus(){
        return this.$store.getters.getCafeLikeActionStatus;
      },

      /*
        Determines if the cafe is still processing the un-like action.
      */
      cafeUnlikeActionStatus(){
        return this.$store.getters.getCafeUnlikeActionStatus;
      }
    },

    methods: {
      likeCafe( cafeID ){
        this.$store.dispatch( 'likeCafe', {
          id: this.cafe.id
        });
      },

      unlikeCafe( cafeID ){
        this.$store.dispatch( 'unlikeCafe', {
          id: this.cafe.id
        });
      }
    }
  }
</script>
