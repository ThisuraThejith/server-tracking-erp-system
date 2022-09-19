<template>
  <div>
    <h1>Ram Details of The Server</h1>
    <RamsList :assetId="$route.query.assetId"/>
  </div>
</template>
<script>
import RamsList from "../components/RamsList";
export default {
  components: {RamsList},
  created() {
    console.log(this.$route)
  }
}
</script>