#include<time.h>
// Your code goes here...
#include <stdio.h>
int main() {
clock_t tStart = clock();

    int a, b;
    scanf("%d%d", & a, & b);
    int d = a - b;
    printf("%d", d);
    
printf("[mtime]%.2fs[/mtime]", (double)(clock() - tStart)/CLOCKS_PER_SEC);
return 0;
}