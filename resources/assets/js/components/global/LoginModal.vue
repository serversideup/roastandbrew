<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#login-modal{
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba( 0, 0, 0, .6 );
    z-index: 99999;

    div.login-box{
      width: 100%;
      max-width: 530px;
      min-width: 320px;
      padding: 20px;
      background-color: #fff;
      border: 1px solid #ddd;
      -webkit-box-shadow: 0 1px 3px rgba(50,50,50,0.08);
      box-shadow: 0 1px 3px rgba(50,50,50,0.08);
      -webkit-border-radius: 4px;
      border-radius: 4px;
      font-size: 16px;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);

      a.social-link{
        display: block;
        margin: auto;
        width: 230px;
        margin-top: 10px;
        margin-bottom: 10px;
      }

      div.login-label{
        color: black;
        font-family: "Lato", sans-serif;
        font-weight: bold;
        text-transform: uppercase;
        text-align: center;
        margin-top: 40px;
        margin-bottom: 20px;
      }

      p.learn-more-description{
        color: #666666;
        text-align: center;
      }

      a.learn-more-button{
        border: 2px solid $secondary-color;
        border-radius: 3px;
        text-transform: uppercase;
        font-family: "Lato", sans-serif;
        color: $secondary-color;
        width: 360px;
        font-size: 16px;
        text-align: center;
        padding: 10px;
        margin-top: 20px;
        display: block;
        margin: auto;

        &:hover{
          color: white;
          background-color: $secondary-color;
        }
      }
    }
  }

  /* Small only */
  @media screen and (max-width: 39.9375em) {
    div#login-modal{
      div.login-box{
        width: 95%;

        a.learn-more-button{
          width: 300px;
        }
      }
    }
  }

  /* Medium only */
  @media screen and (min-width: 40em) and (max-width: 63.9375em) {

  }

  /* Large only */
  @media screen and (min-width: 64em) and (max-width: 74.9375em) {

  }
</style>

<template>
  <div id="login-modal" v-show="show" v-on:click="show = false">
    <div class="login-box" v-on:click.stop="">
      <div class="login-label">Sign in using your social profile</div>

			<a href="/login/google" class="social-link">
				<img src="/img/google-login.svg"/>
			</a>

			<a href="/login/twitter" class="social-link">
				<img src="/img/twitter-login.svg"/>
			</a>

			<a href="/login/facebook" class="social-link">
				<img src="/img/facebook-login.svg"/>
			</a>

      <div class="login-label">About this project</div>

      <p class="learn-more-description">Roast is a project of <a href="https://serversideup.net" target="_blank">Server Side Up</a>, where we provide resources for people who want to learn software development. Learn more how this app below.</p>

      <a class="learn-more-button" href="https://serversideup.net/series/api-driven-development-laravel-vuejs/" target="_blank">Learn How This App Was Built</a>
		</div>
  </div>
</template>

<script>
  import { EventBus } from '../../event-bus.js';

  export default {
    data(){
      return {
        show: false
      }
    },

    mounted(){
      EventBus.$on('prompt-login', function(){
        this.show = true;
      }.bind(this));
    }
  }
</script>
