#include<stdio.h>
#include <time.h>
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
int main() {
clock_t tStart = clock();

	int n,i;
	scanf("%d", &n);
	for(i=0;i<n;i++){
		scanf("%d", &a[i]);
	}
	Quicksort(a,0,n-1);
	for(i=0;i<n;i++){
		printf("%d ",a[i]);
	}

printf("[mtime]%.2fs[/mtime]", (double)(clock() - tStart)/CLOCKS_PER_SEC);
}