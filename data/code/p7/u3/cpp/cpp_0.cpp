// selection sort
#include<iostream>
int main ()
{
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
}
