#include <time.h>
#include <stdio.h>
#include <stdlib.h>

void sx_nhanh(int x[], int l, int r)
{
    int i, j, key, trg;
    i = l;
    j = r;
    key = x[r];
    do {
        while (x[i] < key) i++;
        while (key < x[j]) j--;
        if (i <= j)
            {
                trg = x[i];
                x[i] = x[j];
                x[j] = trg;
                i++;
                j--;
            }
    }
    while (i<=j);
    if (l < j)    sx_nhanh(x, l, j);
    if (i < r)    sx_nhanh(x, i, r);
}

int x[10000];
int main() {
clock_t tStart = clock();

    int n,i;
    scanf("%d",&n);
    for(i=0;i<n;i++)
    {
        scanf("%d",&x[i]);
    }
    sx_nhanh(x,0,n-1);
  
    for(i=0;i<n;i++) printf("%d\t",x[i]);
	  
printf("[mtime]%.2fs[/mtime]", (double)(clock() - tStart)/CLOCKS_PER_SEC);
return 0;
	
}