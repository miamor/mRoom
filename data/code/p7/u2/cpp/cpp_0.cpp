// quick sort
#include<stdio.h>

void Quicksort(int a[],int left,int right){
	int i,j,x;
	if(left>=right) return;
	x=a[(left+right)/2];
	i= left;j=right;
	do{
		while(a[i]<x) i++;
		while(a[j]>x) j--;
		if(i<=j){
		int temp=a[i];
		a[i]=a[j];
		a[j]=temp;
		i++;
		j--;
		}
	}while(i<j);
	if(left<j) Quicksort(a,left,j);
	if(i<right) Quicksort(a,i,right);
}

int a[10000];
int main(){
	int n,i;
	scanf("%d", &n);
	for(i=0;i<n;i++){
		scanf("%d", &a[i]);
	}
	Quicksort(a,0,n-1);
	for(i=0;i<n;i++){
		printf("%d ",a[i]);
	}
}
