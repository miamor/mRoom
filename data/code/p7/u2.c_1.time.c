#include<stdio.h>
#include <time.h>
// buble sort
#include<stdio.h>
  int main() {
clock_t tStart = clock();

     int n,a[10000];
		scanf("%d", &n);
     for(int i=0;i<n;i++){
            scanf("%d",&a[i]);}
     for(int i=0;i<n;i++){
      for (int j=n-1;j>i;j--){
         if(a[j]<a[j-1]) {
            int tam=a[j-1];
                a[j-1]=a[j];
                a[j]=tam;}

      }}
     for(int i=0;i<n;i++){
        printf("%d ",a[i]);
     }
      
printf("[mtime]%.2fs[/mtime]", (double)(clock() - tStart)/CLOCKS_PER_SEC);
}