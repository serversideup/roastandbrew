<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  footer.app-footer{
    padding: 30px;
    background-color: $dull-color;

    img.ssu-logo{
      max-width: 200px;
      float: right;
    }
  }
</style>

<template>
  <footer class="app-footer">
    <div class="grid-x">
      <div class="large-6 medium-6 small-12 cell">

      </div>
      <div class="large-6 medium-6 small-12 cell">
        <a href="https://serversideup.net" target="_blank">
          <img src="/img/ssu-logo.svg" class="ssu-logo"/>
        </a>
      </div>
    </div>
  </footer>
</template>

<script>
  export default {

  }
</script>
