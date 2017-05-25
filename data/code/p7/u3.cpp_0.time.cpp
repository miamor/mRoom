#include<stdio.h>
#include <time.h>
// selection sort
#include<iostream>
int main() {
clock_t tStart = clock();

    int n,min ,a[10000];
    scanf("%d", &n);
    for(int i=0;i<n;i++){
        scanf("%d",&a[i]);
    }
    for(int i=0;i<n;i++){
        min=i;
        for(int j=i+1;j<n;j++){
            if(a[j]<a[min]) min=j;
        }
        if(min!=i) {
            int tam=a[min];
            a[min]=a[i];
            a[i]=tam;
        }
    }
    for(int i=0;i<n;i++){
        printf("%d ",a[i]);
    }

printf("[mtime]%.2fs[/mtime]", (double)(clock() - tStart)/CLOCKS_PER_SEC);
}