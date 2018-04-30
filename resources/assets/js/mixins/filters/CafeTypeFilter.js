export const CafeTypeFilter = {
  methods: {
    processCafeTypeFilter( cafe, type ){
      switch( type ){
        case 'roasters':
          if( cafe.company.roaster == 1 ){
            return true;
          }else{
            return false;
          }
        break;
        case 'cafes':
          if( cafe.company.roaster == 0 ){
            return true;
          }else{
            return false;
          }
        break;
        case 'all':
          return true;
        break;
      }
    }
  }
}
