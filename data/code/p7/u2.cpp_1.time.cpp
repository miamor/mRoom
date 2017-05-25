#include<stdio.h>
#include <time.h>
// insertion sort
#include<stdio.h>
    int n,a[10000];
  int main() {
clock_t tStart = clock();

	scanf("%d", &n);
	for(int i=0;i<n;i++){
		scanf("%d", &a[i]);
	}
    int vt,x;
    for(int i=1;i<n;i++){
        x=a[i];
        vt=i-1;
        while((vt>=0)&&(a[vt]>x)){
            a[vt+1]=a[vt];
            vt--;
        }
        a[vt+1]=x;
    }
     for(int i=0;i<n;i++){
        printf("%d ",a[i]);
     }
  
printf("[mtime]%.2fs[/mtime]", (double)(clock() - tStart)/CLOCKS_PER_SEC);
}